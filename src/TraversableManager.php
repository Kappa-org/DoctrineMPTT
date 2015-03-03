<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT;

use Kappa\Doctrine\Queries\QueryExecutor;
use Kappa\DoctrineMPTT\Entities\TraversableInterface;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetRoot;
use Kappa\DoctrineMPTT\Queries\QueriesCollector;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

/**
 * Class TraversableManager
 *
 * @package Kappa\DoctrineMPTT
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class TraversableManager
{
	const PREDECESSOR = 0;

	const DESCENDANT = 1;

	/** @var EntityManager */
	private $entityManager;

	/** @var EntityRepository|null */
	private $repository = null;

	/** @var QueryExecutor */
	private $executor;

	/** @var QueriesCollector */
	private $queriesCollector;

	/**
	 * @param EntityManager $entityManager
	 * @param QueryExecutor $executor
	 * @param QueriesCollector $queriesCollector
	 */
	public function __construct(EntityManager $entityManager, QueryExecutor $executor, QueriesCollector $queriesCollector)
	{
		$this->entityManager = $entityManager;
		$this->executor = $executor;
		$this->queriesCollector = $queriesCollector;
	}

	/**
	 * @param Configurator $configurator
	 * @return $this
	 */
	public function setConfigurator(Configurator $configurator)
	{
		$this->queriesCollector->setConfigurator($configurator);

		return $this;
	}

	/**
	 * @return Configurator
	 */
	public function getConfigurator()
	{
		return $this->queriesCollector->getConfigurator();
	}

	/**
	 * @param TraversableInterface $actual
	 * @param TraversableInterface $parent
	 * @param bool $refresh
	 */
	public function insertItem(TraversableInterface $actual, $parent = null, $refresh = true)
	{
		$parent = $this->getEntity($parent);
		if ($parent === null) {
			$parent = $this->getRepository()->fetchOne(new GetRoot($this->getConfigurator()));
		}
		if (!$parent) {
			$this->entityManager->transactional(function () use ($actual) {
				$actual->setLeft(1)
					->setRight(2)
					->setDepth(0);
				$this->entityManager->persist($actual);
				$this->entityManager->flush();
			});
		} else {
			$queriesCollection = $this->queriesCollector->getInsertItemQueries($parent);
			$this->entityManager->transactional(function () use ($queriesCollection, $actual, $parent) {
				$this->executor->execute($queriesCollection);
				$actual->setLeft($parent->getRight())
					->setRight($parent->getRight() + 1)
					->setDepth($parent->getDepth() + 1);
				$this->entityManager->persist($actual);
				$this->entityManager->flush();
			});
		}
		if ($refresh) {
			if ($parent) {
				$this->entityManager->refresh($parent);
			}
			$this->entityManager->refresh($actual);
		}
	}

	/**
	 * @param TraversableInterface|int $actual
	 * @param TraversableInterface|int $related
	 * @param int $moveType
	 * @param bool $refresh
	 * @throws \InvalidArgumentException
	 */
	public function moveItem($actual, $related = null, $moveType, $refresh = true)
	{
		$actual = $this->getEntity($actual);
		$related = $this->getEntity($related);
		$constants = [self::DESCENDANT, self::PREDECESSOR];
		if (!in_array($moveType, $constants)) {
			throw new InvalidArgumentException('Type of move can be only ' . __CLASS__ . '::DESCENDANT or ' . __CLASS__ . '::PREDECESSOR');
		}
		if ($related === null && $moveType == self::PREDECESSOR) {
			throw new InvalidArgumentException("Missing related item for PREDECESSOR move type");
		}
		if ($related === null) {
			$related = $this->getRepository()->fetchOne(new GetRoot($this->getConfigurator()));
		}
		if ($related === null) {
			throw new InvalidArgumentException("Missing related item");
		}
		$difference = $actual->getRight() - $actual->getLeft() + 1;
		if ($moveType == self::PREDECESSOR) {
			$left = $related->getLeft();
			$depth = $related->getDepth();
		} else {
			$left = $related->getRight();
			$depth =  $related->getDepth() + 1;
		}
		if ($left > $actual->getLeft()) {
			$left -= $difference;
		}
		if ($left != $actual->getLeft()) {
			$min_left = min($left, $actual->getLeft());
			$max_right = max($left + $difference - 1, $actual->getRight());
			$move = $left - $actual->getLeft();
			if ($left > $actual->getLeft()) {
				$difference = $difference * -1;
			}
			$queryCollection = $this->queriesCollector->getMoveItemQueries($actual, $depth, $move, $min_left, $max_right, $difference);
			$this->entityManager->transactional(function () use ($queryCollection) {
				$this->executor->execute($queryCollection);
			});
			if ($refresh) {
				$this->entityManager->refresh($actual);
				$this->entityManager->refresh($related);
			}
		}
	}

	/**
	 * @param TraversableInterface|int $actual
	 */
	public function removeItem($actual)
	{
		$actual = $this->getEntity($actual);
		$queryCollection = $this->queriesCollector->getRemoveItemQueries($actual);
		$this->entityManager->transactional(function () use ($queryCollection) {
			$this->executor->execute($queryCollection);
		});
	}

	/**
	 * @param null|int|object $entity
	 * @return null|object
	 */
	private function getEntity($entity = null)
	{
		if ($entity !== null) {
			if (!is_object($entity)) {
				$entity = $this->getRepository()->find($entity);
				if (!$entity) {
					throw new EntityNotFoundException("Entity " . $this->getRepository()->getClassName() . " with id '{$id}' has not been found");
				}
			}
		}
		if ($entity !== null && !$entity instanceof TraversableInterface) {
			throw new InvalidStateException("Entity must implements TraversableInterface");
		}

		return $entity;
	}

	/**
	 * @return \Kdyby\Doctrine\EntityRepository
	 */
	private function getRepository()
	{
		if (!$this->repository instanceof EntityRepository) {
			$this->repository = $this->entityManager->getRepository($this->getConfigurator()->get(Configurator::ENTITY_CLASS));
		}

		return $this->repository;
	}
}
