<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\DoctrineMPTT\Mocks;

use Doctrine\ORM\Mapping as ORM;
use Kappa\DoctrineMPTT\Entities\Traversable;
use Kappa\DoctrineMPTT\Entities\TraversableInterface;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="entities")
 */
class Entity extends BaseEntity implements TraversableInterface
{
	use Identifier;

	use Traversable;

	/**
	 * @param int $left
	 * @param int $right
	 * @param int $depth
	 * @return Entity
	 */
	public static function createDefault($left, $right, $depth)
	{
		$entity = new static();
		$entity->setLeft($left)
			->setRight($right)
			->setDepth($depth);

		return $entity;
	}
}
