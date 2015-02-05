<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\Entities;

/**
 * Class Traversable
 *
 * @package Kappa\DoctrineMPTT\Entities
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
trait Traversable
{
	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $lft;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $rgt;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $depth;

	/**
	 * @return int
	 */
	public function getLeft()
	{
		return $this->lft;
	}

	/**
	 * @param int $left
	 * @return $this
	 */
	public function setLeft($left)
	{
		$this->lft = $left;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getRight()
	{
		return $this->rgt;
	}

	/**
	 * @param int $right
	 * @return $this
	 */
	public function setRight($right)
	{
		$this->rgt = $right;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDepth()
	{
		return $this->depth;
	}

	/**
	 * @param int $depth
	 * @return $this
	 */
	public function setDepth($depth)
	{
		$this->depth = $depth;

		return $this;
	}
}
