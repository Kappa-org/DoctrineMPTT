<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\Queries\Objects\Selectors;

use Kappa\DoctrineMPTT\Configurator;
use Kappa\DoctrineMPTT\Entities\TraversableInterface;
use Kappa\DoctrineMPTT\Utils\StringComposer;
use Kdyby;
use Kdyby\Doctrine\QueryObject;

/**
 * Class GetParent
 *
 * @package KappaTests\DoctrineMPTT\Queries\Selectors
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class GetParent extends QueryObject
{
	/** @var Configurator */
	private $configurator;

	/** @var TraversableInterface */
	private $actual;

	/**
	 * @param Configurator $configurator
	 * @param TraversableInterface $actual
	 */
	public function __construct(Configurator $configurator, TraversableInterface $actual)
	{
		$this->configurator = $configurator;
		$this->actual = $actual;
	}

	/**
	 * @param \Kdyby\Persistence\Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		$stringComposer = new StringComposer([
			':leftName:' => $this->configurator->get(Configurator::LEFT_NAME),
			':rightName:' => $this->configurator->get(Configurator::RIGHT_NAME),
		]);

		return $repository->createQueryBuilder('e')
			->select('e')
			->where($stringComposer->compose('e.:leftName: < ?0'))
			->andWhere($stringComposer->compose('e.:rightName: > ?1'))
			->orderBy($stringComposer->compose('e.:leftName:'), 'DESC')
			->setMaxResults(1)
			->setParameters([
				$this->actual->getLeft(),
				$this->actual->getRight()
			]);
	}
}
