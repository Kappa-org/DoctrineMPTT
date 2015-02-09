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

use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetAll;
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
class GetAllTest extends ORMTestCase
{
	private $configurator;

	protected function setUp()
	{
		parent::setUp();
		$this->configurator = $this->container->getByType('Kappa\DoctrineMPTT\Configurator');
	}

	public function testGetChildren()
	{
		$expected = [
			Entity::createWithId(1, 1, 18, 0),
			Entity::createWithId(2, 2, 5, 1),
			Entity::createWithId(5, 3, 4, 2),
			Entity::createWithId(3, 6, 15, 1),
			Entity::createWithId(6, 7, 8, 2),
			Entity::createWithId(7, 9, 10, 2),
			Entity::createWithId(8, 11, 14, 2),
			Entity::createWithId(9, 12, 13, 3),
			Entity::createWithId(4, 16, 17, 1),
		];
		Assert::equal($expected, $this->repository->fetch(new GetAll($this->configurator))->toArray());
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new GetAllTest());
