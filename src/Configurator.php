<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT;

/**
 * Class Configurator
 *
 * @package Kappa\DoctrineMPTT
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class Configurator
{
	const ENTITY_CLASS = 'entityClass';

	CONST LEFT_NAME = 'leftColumnName';

	const RIGHT_NAME = 'rightColumnName';

	const DEPTH_NAME = 'depthColumnName';

	const ORIGINAL_LEFT_NAME = 'originalLeftName';

	/** @var array */
	private $data = [
		self::ENTITY_CLASS => '',
		self::LEFT_NAME => 'lft',
		self::ORIGINAL_LEFT_NAME => '_lft',
		self::RIGHT_NAME => 'rgt',
		self::DEPTH_NAME => 'depth'
	];

	/**
	 * @param array $data
	 */
	public function __construct(array $data = null)
	{
		if ($data !== null) {
			$this->data = $data;
		}
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function set($key, $value)
	{
		$constants = [self::ENTITY_CLASS, self::DEPTH_NAME, self::LEFT_NAME, self::RIGHT_NAME, self::ORIGINAL_LEFT_NAME];
		if (!in_array($key, $constants)) {
			throw new InvalidArgumentException(__METHOD__ . ": Key must be const ENTITY_CLASS, DEPTH_NAME, LEFT_NAME, RIGHT_NAME or ORIGINAL_LEFT_NAME");
		}
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * @param int $key
	 * @return string|null
	 */
	public function get($key)
	{
		if ($key === self::ENTITY_CLASS && (!isset($this->data[$key]) || !class_exists($this->data[$key]))) {
			throw new MissingClassNamespaceException("You must set ENTITY_CLASS in " . __CLASS__);
		}
		return array_key_exists($key, $this->data) ? $this->data[$key] : NULL;
	}
}
