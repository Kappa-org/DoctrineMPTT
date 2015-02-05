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
 * Interface TraversableInterface
 *
 * @package Kappa\DoctrineMPTT\Entities
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
interface TraversableInterface
{
	/**
	 * @param int $left
	 * @return $this
	 */
	public function setLeft($left);

	/**
	 * @return int
	 */
	public function getLeft();

	/**
	 * @param int $right
	 * @return $this
	 */
	public function setRight($right);

	/**
	 * @return int
	 */
	public function getRight();

	/**
	 * @param int $depth
	 * @return $this
	 */
	public function setDepth($depth);

	/**
	 * @return int
	 */
	public function getDepth();
}
