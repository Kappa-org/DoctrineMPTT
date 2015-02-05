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
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ConfiguratorTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ConfiguratorTest extends TestCase
{
	public function testSet()
	{
		$configurator = new Configurator();
		Assert::type('Kappa\DoctrineMPTT\Configurator', $configurator->set(Configurator::DEPTH_NAME, 'depth'));
		Assert::exception(function () use ($configurator) {
			$configurator->set("some", "");
		}, 'Kappa\DoctrineMPTT\InvalidArgumentException');
	}

	public function testGet()
	{
		$configurator = new Configurator([Configurator::DEPTH_NAME => 'depth']);
		Assert::same('depth', $configurator->get(Configurator::DEPTH_NAME));
		Assert::null($configurator->get("some"));
		Assert::exception(function () use ($configurator) {
			$configurator->get(Configurator::ENTITY_CLASS);
		}, 'Kappa\DoctrineMPTT\MissingClassNamespaceException');
	}
}

\run(new ConfiguratorTest());
