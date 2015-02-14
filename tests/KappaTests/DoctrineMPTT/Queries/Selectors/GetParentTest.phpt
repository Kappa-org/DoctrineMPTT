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

use Kappa\DoctrineMPTT\Configurator;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetParent;
use KappaTests\DoctrineMPTT\ORMTestCase;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Class GetParentTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class GetParentTest extends ORMTestCase
{
	/** @var Configurator */
	private $configurator;

	protected function setUp()
	{
		parent::setUp();
		$this->configurator = $this->container->getByType('Kappa\DoctrineMPTT\Configurator');
	}

	/**
	 * @param int $actualId
	 * @param int $expectedId
	 * @dataProvider provideGetParentData
	 */
	public function testGetParent($actualId, $expectedId)
	{
		$actual = $this->repository->find($actualId);
		Assert::same($expectedId, $this->repository->fetchOne(new GetParent($this->configurator, $actual))->getId());
	}

	public function provideGetParentData()
	{
		// [actualId, expectedId]
		return [
			[9, 8],
			[7, 3],
			[5, 2],
			[4, 1],
			[2, 1]
		];
	}
}

Environment::lock('database', dirname(TEMP_DIR));

\run(new GetParentTest());
