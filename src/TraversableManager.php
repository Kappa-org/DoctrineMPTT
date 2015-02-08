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

use Kappa\Doctrine\InvalidArgumentException;
use Kappa\Doctrine\Queries\QueryExecutor;
use Kappa\DoctrineMPTT\Entities\TraversableInterface;
use Kappa\DoctrineMPTT\Queries\QueriesCollector;
use Kdyby\Doctrine\EntityManager;

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

	/** @var Configurator */
	private $configurator;

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
	 * @param TraversableInterface $parent
	 * @param TraversableInterface $actual
	 * @param bool $refresh
	 */
	public function insertItem(TraversableInterface $parent, TraversableInterface $actual, $refresh = true)
	{
		$queriesCollection = $this->queriesCollector->getInsertItemQueries($parent);
		$this->entityManager->transactional(function () use ($queriesCollection, $actual, $parent, $refresh) {
			$this->executor->execute($queriesCollection);
			$actual->setLeft($parent->getRight())
				->setRight($parent->getRight() + 1)
				->setDepth($parent->getDepth() + 1);
			$this->entityManager->persist($actual);
			$this->entityManager->flush();
			if ($refresh) {
				$this->entityManager->refresh($parent);
				$this->entityManager->refresh($actual);
			}
		});
	}

	/**
	 * @param TraversableInterface $actual
	 * @param TraversableInterface $related
	 * @param int $moveType
	 * @param bool $refresh
	 * @throws \InvalidArgumentException
	 */
	public function moveItem(TraversableInterface $actual, TraversableInterface $related, $moveType, $refresh = true)
	{
		$constants = [self::DESCENDANT, self::PREDECESSOR];
		if (!in_array($moveType, $constants)) {
			throw new InvalidArgumentException('Type of move can be only ' . __CLASS__ . '::DESCENDANT or ' . __CLASS__ . '::PREDECESSOR');
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
	 * @param TraversableInterface $actual
	 */
	public function removeItem(TraversableInterface $actual)
	{
		$queryCollection = $this->queriesCollector->getRemoveItemQueries($actual);
		$this->entityManager->transactional(function () use ($queryCollection) {
			$this->executor->execute($queryCollection);
		});
	}
}
