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

use Kappa\Doctrine\Queries\QueryExecutor;
use Kappa\DoctrineMPTT\Configurator;
use Kappa\DoctrineMPTT\Queries\QueriesCollector;
use Kappa\DoctrineMPTT\TraversableManager;
use KappaTests\DoctrineMPTT\Mocks\Entity;
use KappaTests\DoctrineMPTT\ORMTestCase;
use Tester\Assert;
use Tester\Environment;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class TraversableManagerTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class TraversableManagerTest extends ORMTestCase
{
	/** @var TraversableManager */
	private $traversableManager;

	protected function setUp()
	{
		parent::setUp();
		$configurator = new Configurator($this->em);
		$configurator->set(Configurator::ENTITY_CLASS, 'KappaTests\DoctrineMPTT\Mocks\Entity');
		$queriesCollector = new QueriesCollector($this->em, $configurator);
		$this->traversableManager = new TraversableManager($this->em, new QueryExecutor($this->em), $queriesCollector);
	}

	/**
	 * @param int|null $parentId
	 * @param array $expected
	 * @dataProvider provideInsertItemData
	 */
	public function testInsertItemByEntity($parentId, array $expected)
	{
		$this->sqlLogger->startSection();
		if ($parentId) {
			$parent = $this->repository->find(2);
		} else {
			$parent = $parentId;
		}
		$this->traversableManager->insertItem(new Entity(), $parent);
		Assert::equal($expected, $this->repository->findBy([], ['id' => 'ASC']));
		$this->sqlLogger->stopSection();
	}

	/**
	 * @param int|null $parent
	 * @param array $expected
	 * @dataProvider provideInsertItemData
	 */
	public function testInsertItemById($parent, array $expected)
	{
		$this->sqlLogger->startSection();
		$this->traversableManager->insertItem(new Entity(), $parent);
		Assert::equal($expected, $this->repository->findBy([], ['id' => 'ASC']));
		$this->sqlLogger->stopSection();
	}

	public function provideInsertItemData()
	{
		// [parentId, expected]
		return [
			[2, [
				Entity::createWithId(1, 1, 20, 0),
				Entity::createWithId(2, 2, 7, 1),
				Entity::createWithId(3, 8, 17, 1),
				Entity::createWithId(4, 18, 19, 1),
				Entity::createWithId(5, 3, 4, 2),
				Entity::createWithId(6, 9, 10, 2),
				Entity::createWithId(7, 11, 12, 2),
				Entity::createWithId(8, 13, 16, 2),
				Entity::createWithId(9, 14, 15, 3),
				Entity::createWithId(10, 5, 6, 2),
			]],
			[null, [Entity::createWithId(1, 1, 20, 0),
				Entity::createWithId(2, 2, 5, 1),
				Entity::createWithId(3, 6, 15, 1),
				Entity::createWithId(4, 16, 17, 1),
				Entity::createWithId(5, 3, 4, 2),
				Entity::createWithId(6, 7, 8, 2),
				Entity::createWithId(7, 9, 10, 2),
				Entity::createWithId(8, 11, 14, 2),
				Entity::createWithId(9, 12, 13, 3),
				Entity::createWithId(10, 18, 19, 1),]]
		];
	}

	public function testInsertItemAsRoot()
	{
		$entities = $this->repository->findAll();
		foreach ($entities as $entity) {
			$this->em->remove($entities);
		}
		$this->em->flush();
		Assert::count(0, $this->repository->findAll());
		$this->sqlLogger->startSection();
		$this->traversableManager->insertItem(new Entity());
		$this->sqlLogger->stopSection();
		$entities = $this->repository->findAll();
		Assert::count(1, $entities);
		$entity = $entities[0];
		Assert::same(1, $entity->getLeft());
		Assert::same(2, $entity->getRight());
		Assert::same(0, $entity->getDepth());
	}

	/**
	 * @param int $actual
	 * @param int $related
	 * @param int $moveType
	 * @param array $expected
	 * @dataProvider provideMoveItemData
	 */
	public function testMoveItemByEntity($actual, $related, $moveType, array $expected)
	{
		$this->sqlLogger->startSection();
		$actual = $this->repository->find($actual);
		if ($related) {
			$related = $this->repository->find($related);
		}
		$this->traversableManager->moveItem($actual, $related, $moveType);
		Assert::equal($expected, $this->repository->findBy([], ['id' => 'ASC']));
		$this->sqlLogger->stopSection();
	}

	/**
	 * @param int $actual
	 * @param int $related
	 * @param int $moveType
	 * @param array $expected
	 * @dataProvider provideMoveItemData
	 */
	public function testMoveItemById($actual, $related, $moveType, array $expected)
	{
		$this->sqlLogger->startSection();
		$this->traversableManager->moveItem($actual, $related, $moveType);
		Assert::equal($expected, $this->repository->findBy([], ['id' => 'ASC']));
		$this->sqlLogger->stopSection();
	}

	/**
	 * @return array
	 */
	public function provideMoveItemData()
	{
		// [actual, related, moveType, expected]
		return [
			[3, 4, TraversableManager::DESCENDANT, [
				Entity::createWithId(1, 1, 18, 0),
				Entity::createWithId(2, 2, 5, 1),
				Entity::createWithId(3, 7, 16, 2),
				Entity::createWithId(4, 6, 17, 1),
				Entity::createWithId(5, 3, 4, 2),
				Entity::createWithId(6, 8, 9, 3),
				Entity::createWithId(7, 10, 11, 3),
				Entity::createWithId(8, 12, 15, 3),
				Entity::createWithId(9, 13, 14, 4),
			]],
			[8, 2, TraversableManager::DESCENDANT, [
				Entity::createWithId(1, 1, 18, 0),
				Entity::createWithId(2, 2, 9, 1),
				Entity::createWithId(3, 10, 15, 1),
				Entity::createWithId(4, 16, 17, 1),
				Entity::createWithId(5, 3, 4, 2),
				Entity::createWithId(6, 11, 12, 2),
				Entity::createWithId(7, 13, 14, 2),
				Entity::createWithId(8, 5, 8, 2),
				Entity::createWithId(9, 6, 7, 3),
			]],
			[3, 2, TraversableManager::PREDECESSOR, [
				Entity::createWithId(1, 1, 18, 0),
				Entity::createWithId(2, 12, 15, 1),
				Entity::createWithId(3, 2, 11, 1),
				Entity::createWithId(4, 16, 17, 1),
				Entity::createWithId(5, 13, 14, 2),
				Entity::createWithId(6, 3, 4, 2),
				Entity::createWithId(7, 5, 6, 2),
				Entity::createWithId(8, 7, 10, 2),
				Entity::createWithId(9, 8, 9, 3),
			]],
			[4, 3, TraversableManager::PREDECESSOR, [
				Entity::createWithId(1, 1, 18, 0),
				Entity::createWithId(2, 2, 5, 1),
				Entity::createWithId(3, 8, 17, 1),
				Entity::createWithId(4, 6, 7, 1),
				Entity::createWithId(5, 3, 4, 2),
				Entity::createWithId(6, 9, 10, 2),
				Entity::createWithId(7, 11, 12, 2),
				Entity::createWithId(8, 13, 16, 2),
				Entity::createWithId(9, 14, 15, 3),
			]],
			[3, null, TraversableManager::DESCENDANT, [
				Entity::createWithId(1, 1, 18, 0),
				Entity::createWithId(2, 2, 5, 1),
				Entity::createWithId(3, 8, 17, 1),
				Entity::createWithId(4, 6, 7, 1),
				Entity::createWithId(5, 3, 4, 2),
				Entity::createWithId(6, 9, 10, 2),
				Entity::createWithId(7, 11, 12, 2),
				Entity::createWithId(8, 13, 16, 2),
				Entity::createWithId(9, 14, 15, 3),
			]],
		];
	}

	public function testMissingRelatedItemForMoveItem()
	{
		Assert::exception(function () {
			$this->traversableManager->moveItem($this->repository->find(1), null, TraversableManager::PREDECESSOR);
		}, 'Kappa\DoctrineMPTT\InvalidArgumentException');
	}

	/**
	 * @param int $id
	 * @param array$expected
	 * @dataProvider provideRemoveItemData
	 */
	public function testRemoveItemByEntity($id, array $expected)
	{
		$actual = $this->repository->find($id);
		$this->traversableManager->removeItem($actual);
		Assert::equal($expected, $this->repository->findBy([], ['id' => 'ASC']));
	}

	/**
	 * @param int $id
	 * @param array$expected
	 * @dataProvider provideRemoveItemData
	 */
	public function testRemoveItemById($id, array $expected)
	{
		$this->traversableManager->removeItem($id);
		Assert::equal($expected, $this->repository->findBy([], ['id' => 'ASC']));
	}

	public function provideRemoveItemData()
	{
		// [id, array expected]
		return [
			[1, []],
			[2, [
				Entity::createWithId(1, 1, 14, 0),
				Entity::createWithId(3, 2, 11, 1),
				Entity::createWithId(4, 12, 13, 1),
				Entity::createWithId(6, 3, 4, 2),
				Entity::createWithId(7, 5, 6, 2),
				Entity::createWithId(8, 7, 10, 2),
				Entity::createWithId(9, 8, 9, 3),
			]],
			[3, [
				Entity::createWithId(1, 1, 8, 0),
				Entity::createWithId(2, 2, 5, 1),
				Entity::createWithId(4, 6, 7, 1),
				Entity::createWithId(5, 3, 4, 2),
			]],
			[6, [
				Entity::createWithId(1, 1, 16, 0),
				Entity::createWithId(2, 2, 5, 1),
				Entity::createWithId(3, 6, 13, 1),
				Entity::createWithId(4, 14, 15, 1),
				Entity::createWithId(5, 3, 4, 2),
				Entity::createWithId(7, 7, 8, 2),
				Entity::createWithId(8, 9, 12, 2),
				Entity::createWithId(9, 10, 11, 3),
			]],
			[8, [
				Entity::createWithId(1, 1, 14, 0),
				Entity::createWithId(2, 2, 5, 1),
				Entity::createWithId(3, 6, 11, 1),
				Entity::createWithId(4, 12, 13, 1),
				Entity::createWithId(5, 3, 4, 2),
				Entity::createWithId(6, 7, 8, 2),
				Entity::createWithId(7, 9, 10, 2),
			]],
		];
	}
}

Environment::lock("database", dirname(TEMP_DIR));

\run(new TraversableManagerTest());
