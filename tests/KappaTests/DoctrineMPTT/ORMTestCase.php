<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\DoctrineMPTT;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\ORM\Tools\SchemaTool;
use KappaTests\DoctrineMPTT\Mocks\Fixtures\LoadDefaultSchema;

/**
 * Class ORMTestCase
 *
 * @package KappaTests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ORMTestCase extends DITestCase
{
	/** @var \Kdyby\Doctrine\EntityManager */
	protected $em;

	/** @var \Kdyby\Doctrine\EntityRepository */
	protected $repository;

	protected function setUp()
	{
		parent::setUp();
		$this->em = $this->container->getByType('Kdyby\Doctrine\EntityManager');
		$this->em->getConnection()->getConfiguration()->setSQLLogger(new SqlLogger());
		$this->repository = $this->em->getRepository('KappaTests\DoctrineMPTT\Mocks\Entity');
		$this->createSchema();
		$this->loadFixtures();
	}

	private function createSchema()
	{
		$classes = [
			$this->em->getClassMetadata('KappaTests\DoctrineMPTT\Mocks\Entity')
		];
		$schemaTool = new SchemaTool($this->em);
		$schemaTool->dropSchema($classes);
		$schemaTool->createSchema($classes);
	}

	private function loadFixtures()
	{
		$loader = new Loader();
		$loader->addFixture(new LoadDefaultSchema());
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures());
	}
}
