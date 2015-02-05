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

/**
 * Class SqlLogger
 *
 * @package KappaTests\DoctrineMPTT
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class SqlLogger implements \Doctrine\DBAL\Logging\SQLLogger
{
	private $queries = [];

	/**
	 * Logs a SQL statement somewhere.
	 *
	 * @param string $sql The SQL to be executed.
	 * @param array|null $params The SQL parameters.
	 * @param array|null $types The SQL parameter types.
	 *
	 * @return void
	 */
	public function startQuery($sql, array $params = null, array $types = null)
	{
		$this->queries[] = $sql;
	}

	/**
	 * Marks the last started query as stopped. This can be used for timing of queries.
	 *
	 * @return void
	 */
	public function stopQuery()
	{

	}

	public function getQueries()
	{
		return $this->queries;
	}
}
