<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\QueryObjects\Updates;

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
class UpdateRightForInsertItem implements Executable
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
			':rightName' => $this->configurator->get(Configurator::RIGHT_NAME),
		]);
		$class = $this->configurator->get(Configurator::ENTITY_CLASS);
		return $queryBuilder->update($class, 'e')
			->set($stringComposer->compose('e.:rightName'), $stringComposer->compose('e.:rightName + 2'))
			->where($stringComposer->compose('e.:rightName') . ' >= :parentRight')
			->setParameter('parentRight', $this->related->getRight());
	}
}
