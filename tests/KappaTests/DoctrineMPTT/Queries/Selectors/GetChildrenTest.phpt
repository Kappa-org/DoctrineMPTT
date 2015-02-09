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

namespace KappaTests\DoctrineMPTT\Queries\Selectors;

use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetChildren;
use KappaTests\DoctrineMPTT\Mocks\Entity;
use KappaTests\DoctrineMPTT\ORMTestCase;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Class GetChildrenTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class GetChildrenTest extends ORMTestCase
{
	private $configurator;

	protected function setUp()
	{
		parent::setUp();
		$this->configurator = $this->container->getByType('Kappa\DoctrineMPTT\Configurator');
	}

	public function testGetChildren()
	{
		$actual = $this->repository->find(3);
		$expected = [
			Entity::createWithId(6, 7, 8, 2),
			Entity::createWithId(7, 9, 10, 2),
			Entity::createWithId(8, 11, 14, 2),
			Entity::createWithId(9, 12, 13, 3),
		];
		Assert::equal($expected, $this->repository->fetch(new GetChildren($this->configurator, $actual))->toArray());
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new GetChildrenTest());
