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
 * Class MoveUpdate
 *
 * @package Kappa\DoctrineMPTT\Queries
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class MoveUpdate implements Executable
{
	/** @var Configurator */
	private $configurator;

	/** @var TraversableInterface */
	private $actual;

	/** @var int */
	private $depth;

	/** @var int */
	private $move;

	/** @var int */
	private $min_left;

	/** @var int */
	private $max_right;

	/** @var int */
	private $difference;

	/**
	 * @param Configurator $configurator
	 * @param TraversableInterface $actual
	 * @param int $depth
	 * @param int $move
	 * @param int $min_left
	 * @param int $max_right
	 * @param int $difference
	 */
	public function __construct(Configurator $configurator, TraversableInterface $actual, $depth, $move, $min_left, $max_right, $difference)
	{
		$this->configurator = $configurator;
		$this->actual = $actual;
		$this->depth = $depth;
		$this->move = $move;
		$this->min_left = $min_left;
		$this->max_right = $max_right;
		$this->difference = $difference;
	}

	/**
	 * @param QueryBuilder $queryBuilder
	 * @return QueryBuilder
	 */
	public function build(QueryBuilder $queryBuilder)
	{
		$class = $this->configurator->get(Configurator::ENTITY_CLASS);
		$depthName = $this->configurator->get(Configurator::DEPTH_NAME);
		$leftName = $this->configurator->get(Configurator::LEFT_NAME);
		$_leftName = $this->configurator->get(Configurator::ORIGINAL_LEFT_NAME);
		$rightName = $this->configurator->get(Configurator::RIGHT_NAME);
		$placeholders = [
			':depthName:' => $depthName,
			':leftName:' => $leftName,
			':_leftName:' => $_leftName,
			':rightName:' => $rightName,
			':actualLeft:' => $this->actual->getLeft(),
			':actualRight:' => $this->actual->getRight(),
			':addDepth:' => $this->depth - $this->actual->getDepth(),
			':move:' => $this->move,
			':minLeft:' => $this->min_left,
			':maxRight:' => $this->max_right,
			':difference:' => $this->difference
		];
		$stringComposer = new StringComposer($placeholders);
		$queryBuilder->update($class, 'e')
			->set($stringComposer->compose('e.:depthName:'), $stringComposer->compose('e.:depthName: + (CASE WHEN e.:leftName: >= :actualLeft: AND e.:rightName: <= :actualRight: THEN :addDepth: ELSE 0 END)'))
			->set($stringComposer->compose('e.:leftName:'), $stringComposer->compose('e.:leftName: + (CASE WHEN e.:leftName: >= :actualLeft: AND e.:rightName: <= :actualRight: THEN :move: ELSE (CASE WHEN e.:leftName: >= :minLeft: THEN :difference: ELSE 0 END) END)'))
			->set($stringComposer->compose('e.:rightName:'), $stringComposer->compose('e.:rightName: + (CASE WHEN e.:_leftName: >= :actualLeft: AND e.:rightName: <= :actualRight: THEN :move: ELSE (CASE WHEN e.:rightName: <= :maxRight: THEN :difference: ELSE 0 END) END)'))
			->set($stringComposer->compose('e.:_leftName:'), $stringComposer->compose('e.:leftName:'))
			->where('e.rgt >= ?0')
			->andWhere('e.lft <= ?1')
			->setParameters([$this->min_left, $this->max_right]);

		return $queryBuilder;
	}
}
