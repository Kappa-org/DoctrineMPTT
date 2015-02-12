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

use Kdyby\Doctrine\EntityManager;

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
		self::ENTITY_CLASS => null,
		self::LEFT_NAME => 'lft',
		self::ORIGINAL_LEFT_NAME => '_lft',
		self::RIGHT_NAME => 'rgt',
		self::DEPTH_NAME => 'depth'
	];

	/** @var \Kdyby\Doctrine\EntityManager */
	private $entityManager;

	/**
	 * @param \Kdyby\Doctrine\EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @param array $data
	 * @return $this
	 */
	public function setData(array $data)
	{
		$this->data = $data;

		return $this;
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
		if ($key === self::ENTITY_CLASS && !array_key_exists($key, $this->data)) {
			throw new MissingClassNamespaceException("You must set ENTITY_CLASS in " . __CLASS__);
		}
		if (array_key_exists($key, $this->data)) {
			if ($key == self::ENTITY_CLASS && $this->data[$key] != null) {
				return $this->entityManager->getClassMetadata($this->data[$key])->getName();
			}

			return $this->data[$key];
		} else {
			return null;
		}
	}
}
