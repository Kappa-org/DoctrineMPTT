<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\Queries\Objects\Manipulators;

use Kappa\Doctrine\Queries\Executable;
use Kappa\DoctrineMPTT\Configurator;
use Kappa\DoctrineMPTT\Entities\TraversableInterface;
use Kappa\DoctrineMPTT\Utils\StringComposer;
use Kdyby\Doctrine\QueryBuilder;

/**
 * Class DeleteItemQuery
 *
 * @package Kappa\DoctrineMPTT\QueryObjects\Updates
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class DeleteItemQuery implements Executable
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
	 * @param QueryBuilder $queryBuilder
	 * @return QueryBuilder
	 */
	public function build(QueryBuilder $queryBuilder)
	{
		$class = $this->configurator->get(Configurator::ENTITY_CLASS);
		$stringComposer = new StringComposer([
			':leftName:' => $this->configurator->get(Configurator::LEFT_NAME),
			':rightName:' => $this->configurator->get(Configurator::RIGHT_NAME)
		]);
		$queryBuilder->delete($class, 'e')
			->where($stringComposer->compose('e.:leftName: >= ?0'))
			->andWhere($stringComposer->compose('e.:rightName: <= ?1'))
			->setParameters([$this->actual->getLeft(), $this->actual->getRight()]);

		return $queryBuilder;
	}
}
