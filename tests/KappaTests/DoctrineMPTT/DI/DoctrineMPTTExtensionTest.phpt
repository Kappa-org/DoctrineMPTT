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

namespace Kappa\DoctrineMPTT\Tests;

use Kappa\DoctrineMPTT\Configurator;
use KappaTests\DoctrineMPTT\DITestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class DoctrineMPTTExtensionTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class DoctrineMPTTExtensionTest extends DITestCase
{
	public function testTraversableManager()
	{
		$type = 'Kappa\DoctrineMPTT\TraversableManager';
		Assert::type($type, $this->container->getByType($type));
	}

	public function testQueryCollector()
	{
		$type = 'Kappa\DoctrineMPTT\Queries\QueriesCollector';
		$service = $this->container->getByType($type);
		Assert::type($type, $service);
		Assert::type('Kappa\DoctrineMPTT\Configurator', $service->getConfigurator());
	}

	public function testConfigurator()
	{
		$type = 'Kappa\DoctrineMPTT\Configurator';
		$configurator = $this->container->getByType($type);
		Assert::type($type, $configurator);
		Assert::same('lft', $configurator->get(Configurator::LEFT_NAME));
		Assert::same('_lft', $configurator->get(Configurator::ORIGINAL_LEFT_NAME));
		Assert::same('rgt', $configurator->get(Configurator::RIGHT_NAME));
		Assert::same('depth', $configurator->get(Configurator::DEPTH_NAME));
	}
}

\run(new DoctrineMPTTExtensionTest());
