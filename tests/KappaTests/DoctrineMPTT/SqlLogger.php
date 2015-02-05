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
	/** @var array */
	private $queries = [];

	/** @var string */
	private $section = "initialize";

	/** @var string */
	private $directory;

	/**
	 * @param $directory
	 * @throws \Exception
	 */
	public function __construct($directory)
	{
		if (!file_exists($directory)) {
			throw new \Exception("Missing directory");
		}
		$this->directory = $directory;
	}

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
		if (array_key_exists($this->section, $this->queries)) {
			$this->queries[$this->section][] = $sql;
		} else {
			$this->queries[$this->section] = [$sql];
		}
	}

	/**
	 * Marks the last started query as stopped. This can be used for timing of queries.
	 *
	 * @return void
	 */
	public function stopQuery()
	{
	}

	public function startSection()
	{
		$trace = debug_backtrace();
		$this->section = $trace[1]['function'];
	}

	public function stopSection()
	{
		$content = '';
		foreach ($this->queries[$this->section] as $key => $sql) {
			$content .= $key + 1 . " => " . $sql . PHP_EOL;
		}

		file_put_contents($this->directory . DIRECTORY_SEPARATOR . $this->section . '.log', $content);
	}
}
