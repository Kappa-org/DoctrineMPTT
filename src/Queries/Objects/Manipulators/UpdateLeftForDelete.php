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
 * Class UpdateLeftForDelete
 *
 * @package Kappa\DoctrineMPTT\QueryObjects\Updates
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class UpdateLeftForDelete implements Executable
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
			':_leftName:' => $this->configurator->get(Configurator::ORIGINAL_LEFT_NAME),
			':difference:' => $this->actual->getRight() - $this->actual->getLeft() + 1
		]);
		$queryBuilder->update($class, 'e')
			->set($stringComposer->compose('e.:leftName:'), $stringComposer->compose('e.:leftName: - :difference:'))
			->set($stringComposer->compose('e.:_leftName:'), $stringComposer->compose('e.:leftName:'))
			->where($stringComposer->compose('e.:leftName: > ?0'))
			->setParameters([$this->actual->getRight()]);

		return $queryBuilder;
	}
}
