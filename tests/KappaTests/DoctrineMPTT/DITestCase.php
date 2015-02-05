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

use Nette\Configurator;
use Tester\TestCase;

/**
 * Class DITestCase
 *
 * @package KappaTests\DoctrineMPTT
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class DITestCase extends TestCase
{
	/** @var \Nette\DI\Container */
	protected $container;

	protected function setUp()
	{
		$configurator = new Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../../data/config.neon');
		$this->container = $configurator->createContainer();
	}
}
