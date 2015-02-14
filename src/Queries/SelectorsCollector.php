<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\Queries;

use Kappa\DoctrineMPTT\Configurator;
use Kappa\DoctrineMPTT\Entities\TraversableInterface;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetAll;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetChildren;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetNext;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetParent;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetParents;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetPrevious;

/**
 * Class SelectorsCollector
 *
 * @package Kappa\DoctrineMPTT\Queries
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class SelectorsCollector
{
	/** @var Configurator */
	private $configurator;

	/**
	 * @param Configurator $configurator
	 */
	public function __construct(Configurator $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * @return GetAll
	 */
	public function getAll()
	{
		return new GetAll($this->configurator);
	}

	/**
	 * @param TraversableInterface $actual
	 * @return GetChildren
	 */
	public function getChildren(TraversableInterface $actual)
	{
		return new GetChildren($this->configurator, $actual);
	}

	/**
	 * @param TraversableInterface $actual
	 * @return GetNext
	 */
	public function getNext(TraversableInterface $actual)
	{
		return new GetNext($this->configurator, $actual);
	}

	/**
	 * @param TraversableInterface $actual
	 * @return GetParents
	 */
	public function getParents(TraversableInterface $actual)
	{
		return new GetParents($this->configurator, $actual);
	}

	/**
	 * @param TraversableInterface $actual
	 * @return GetPrevious
	 */
	public function getPrevious(TraversableInterface $actual)
	{
		return new GetPrevious($this->configurator, $actual);
	}

	/**
	 * @param TraversableInterface $actual
	 * @return GetParent
	 */
	public function getParent(TraversableInterface $actual)
	{
		return new GetParent($this->configurator, $actual);
	}
}
