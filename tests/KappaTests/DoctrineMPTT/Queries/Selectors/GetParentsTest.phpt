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

use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetParents;
use KappaTests\DoctrineMPTT\Mocks\Entity;
use KappaTests\DoctrineMPTT\ORMTestCase;
use KappaTests\Mocks\GetAllQueryObject;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Class GetParentsTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class GetParentsTest extends ORMTestCase
{
	/** @var \Kappa\DoctrineMPTT\Configurator */
	private $configurator;

	protected function setUp()
	{
		parent::setUp();
		$this->configurator = $this->container->getByType('Kappa\DoctrineMPTT\Configurator');
	}

	public function testGetParents()
	{
		$actual = $this->repository->find(9);
		$expected = [
			Entity::createWithId(1, 1, 18, 0),
			Entity::createWithId(3, 6, 15, 1),
			Entity::createWithId(8, 11, 14, 2),
		];
		Assert::equal($expected, $this->repository->fetch(new GetParents($this->configurator, $actual))->toArray());
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new GetParentsTest());
