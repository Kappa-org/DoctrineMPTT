<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\DI;

use Kappa\DoctrineMPTT\Configurator;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;

/**
 * Class DoctrineMPTTExtension
 *
 * @package Kappa\DoctrineMPTT\DI
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class DoctrineMPTTExtension extends CompilerExtension
{
	private $defaultConfig = [
		Configurator::ENTITY_CLASS => null,
		Configurator::ORIGINAL_LEFT_NAME => '_lft',
		Configurator::LEFT_NAME => 'lft',
		Configurator::RIGHT_NAME => 'rgt',
		Configurator::DEPTH_NAME => 'depth'
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultConfig);
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('traversableManager'))
			->setClass('Kappa\DoctrineMPTT\TraversableManager')
			->addSetup('setConfigurator', [new Statement('Kappa\DoctrineMPTT\Configurator', [$config])]);
	}
}
