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

use Kappa\DoctrineMPTT\Queries\Objects\Selectors\GetRoot;
use KappaTests\DoctrineMPTT\ORMTestCase;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Class GetParentsTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class GetRootTest extends ORMTestCase
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
		$entity = $this->repository->fetchOne(new GetRoot($this->configurator));
		Assert::same(1, $entity->getLeft());
		Assert::equal(0, $entity->getDepth());
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new GetRootTest());
