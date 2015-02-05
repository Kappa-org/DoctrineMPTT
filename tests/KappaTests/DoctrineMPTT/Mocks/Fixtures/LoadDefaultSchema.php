<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\DoctrineMPTT\Mocks\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KappaTests\DoctrineMPTT\Mocks\Entity;

/**
 * Class LoadDefaultSchema
 *
 * @package KappaTests\DoctrineMPTT\Mocks\Fixtures
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class LoadDefaultSchema implements FixtureInterface
{
	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param ObjectManager $manager
	 */
	function load(ObjectManager $manager)
	{
		$entities = [
			Entity::createDefault(1, 18, 0), // 1
			Entity::createDefault(2, 5, 1), // 2
			Entity::createDefault(6, 15, 1), // 3
			Entity::createDefault(16, 17, 1), // 4
			Entity::createDefault(3, 4, 2), // 5
			Entity::createDefault(7, 8, 2), // 6
			Entity::createDefault(9, 10, 2), // 7
			Entity::createDefault(11, 14, 2), // 8
			Entity::createDefault(12, 13, 3), // 9
		];
		foreach ($entities as $entity) {
			$manager->persist($entity);
		}
		$manager->flush();
	}
}
