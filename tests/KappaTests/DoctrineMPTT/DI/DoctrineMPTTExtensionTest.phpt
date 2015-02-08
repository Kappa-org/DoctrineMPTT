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
		$service = $this->container->getByType($type);
		Assert::type($type, $service);
		Assert::type('Kappa\DoctrineMPTT\Configurator', $service->getConfigurator());
	}
}

\run(new DoctrineMPTTExtensionTest());
