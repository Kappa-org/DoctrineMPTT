<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace KappaTests\DoctrineMPTT;

use Kappa\DoctrineMPTT\Configurator;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ConfiguratorTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ConfiguratorTest extends DITestCase
{
	private $em;

	protected function setUp()
	{
		parent::setUp();
		$this->em = $this->container->getByType('Kdyby\Doctrine\EntityManager');
	}

	public function testSet()
	{
		$configurator = new Configurator($this->em);
		Assert::type('Kappa\DoctrineMPTT\Configurator', $configurator->set(Configurator::DEPTH_NAME, 'depth'));
		Assert::exception(function () use ($configurator) {
			$configurator->set("some", "");
		}, 'Kappa\DoctrineMPTT\InvalidArgumentException');
	}

	public function testGet()
	{
		$configurator = new Configurator($this->em);
		$configurator->setData([Configurator::DEPTH_NAME => 'depth']);
		Assert::same('depth', $configurator->get(Configurator::DEPTH_NAME));
		Assert::null($configurator->get("some"));
		Assert::exception(function () use ($configurator) {
			$configurator->get(Configurator::ENTITY_CLASS);
		}, 'Kappa\DoctrineMPTT\MissingClassNamespaceException');
	}

	public function testGetClass()
	{
		$configurator = new Configurator($this->em);
		$class = 'KappaTests\DoctrineMPTT\Mocks\Entity';
		$configurator->setData([
			Configurator::ENTITY_CLASS => $class,
		]);
		Assert::same($class, $configurator->get(Configurator::ENTITY_CLASS));
	}
}

\run(new ConfiguratorTest());
