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

use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetNext;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetParents;
use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetPrevious;
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
class GetNextTest extends ORMTestCase
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
		$actual = $this->repository->find(3);
		Assert::equal(4, $this->repository->fetchOne(new GetNext($this->configurator, $actual))->getId());
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new GetNextTest());
