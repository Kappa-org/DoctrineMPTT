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
 * Class UpdateLeftForInsertItem
 *
 * @package Kappa\DoctrineMPTT\Queries
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class UpdateLeftForInsertItem implements Executable
{
	/** @var Configurator */
	private $configurator;

	/** @var TraversableInterface */
	private $related;

	/**
	 * @param Configurator $configurator
	 * @param TraversableInterface $related
	 */
	public function __construct(Configurator $configurator, TraversableInterface $related)
	{
		$this->configurator = $configurator;
		$this->related = $related;
	}

	/**
	 * @param QueryBuilder $queryBuilder
	 * @return QueryBuilder
	 */
	public function build(QueryBuilder $queryBuilder)
	{
		$stringComposer = new StringComposer([
			':leftName' => $this->configurator->get(Configurator::LEFT_NAME),
			':originalLeftName' => $this->configurator->get(Configurator::ORIGINAL_LEFT_NAME),
		]);
		$class = $this->configurator->get(Configurator::ENTITY_CLASS);
		$queryBuilder->update($class, 'e')
			->set($stringComposer->compose('e.:leftName'), $stringComposer->compose('e.:leftName + 2'))
			->set($stringComposer->compose('e.:originalLeftName'), $stringComposer->compose('e.:leftName'))
			->where($stringComposer->compose('e.:leftName') . ' > :parentRight')
			->setParameter('parentRight', $this->related->getRight());

		return $queryBuilder;
	}
}
