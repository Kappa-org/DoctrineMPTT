<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\Queries;

use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Kappa\Doctrine\Queries\ExecutableCollection;
use Kappa\DoctrineMPTT\Configurator;
use Kappa\DoctrineMPTT\Entities\TraversableInterface;
use Kappa\DoctrineMPTT\MissingConfiguratorException;
use Kappa\DoctrineMPTT\Queries\Objects\Manipulators\DeleteItemQuery;
use Kappa\DoctrineMPTT\Queries\Objects\Manipulators\MoveUpdate;
use Kappa\DoctrineMPTT\Queries\Objects\Manipulators\UpdateLeftForDelete;
use Kappa\DoctrineMPTT\Queries\Objects\Manipulators\UpdateLeftForInsertItem;
use Kappa\DoctrineMPTT\Queries\Objects\Manipulators\UpdateRightForDelete;
use Kappa\DoctrineMPTT\Queries\Objects\Manipulators\UpdateRightForInsertItem;
use Kdyby\Doctrine\EntityManager;

/**
 * Class QueriesCollection
 *
 * @package Kappa\DoctrineMPTT\Queries
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class QueriesCollector
{
	/** @var Configurator */
	private $configurator;

	/** @var bool */
	private $isMysql;

	/**
	 * @param EntityManager $entityManager
	 * @param Configurator $configurator
	 */
	public function __construct(EntityManager $entityManager, Configurator $configurator = null)
	{
		$driver = $entityManager->getConnection()->getDriver();
		$this->isMysql = $driver instanceof AbstractMySQLDriver;

		$this->configurator = $configurator;
	}

	/**
	 * @param Configurator $configurator
	 * @return $this
	 */
	public function setConfigurator(Configurator $configurator)
	{
		$this->configurator = $configurator;

		return $this;
	}

	/**
	 * @return Configurator
	 */
	public function getConfigurator()
	{
		if (!$this->configurator) {
			throw new MissingConfiguratorException("You must first set configurator with " . __CLASS__ . "::setConfigurator() method");
		}

		return $this->configurator;
	}

	/**
	 * @param TraversableInterface $actual
	 * @return ExecutableCollection
	 * @throws MissingConfiguratorException
	 */
	public function getRemoveItemQueries(TraversableInterface $actual)
	{
		$collection = new ExecutableCollection([
			new DeleteItemQuery($this->getConfigurator(), $actual),
			new UpdateLeftForDelete($this->getConfigurator(), $actual),
			new UpdateRightForDelete($this->getConfigurator(), $actual)
		]);

		return $collection;
	}

	/**
	 * @param int $actual
	 * @param int $depth
	 * @param int $move
	 * @param int $min_left
	 * @param int $max_right
	 * @param int $difference
	 * @return ExecutableCollection
	 * @throws MissingConfiguratorException
	 */
	public function getMoveItemQueries($actual, $depth, $move, $min_left, $max_right, $difference)
	{
		$collection = new ExecutableCollection([
			new MoveUpdate($this->getConfigurator(), $this->isMysql, $actual, $depth, $move, $min_left, $max_right, $difference)
		]);

		return $collection;
	}

	/**
	 * @param TraversableInterface $parent
	 * @return ExecutableCollection
	 * @throws MissingConfiguratorException
	 */
	public function getInsertItemQueries(TraversableInterface $parent)
	{
		$collection = new ExecutableCollection([
			new UpdateLeftForInsertItem($this->getConfigurator(), $parent),
			new UpdateRightForInsertItem($this->getConfigurator(), $parent)
		]);

		return $collection;
	}
}
