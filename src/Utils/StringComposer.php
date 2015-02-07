<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\Utils;

/**
 * Class StringComposer
 *
 * @package Kappa\DoctrineMPTT\Utils
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class StringComposer
{
	/** @var array */
	private $placeholders;

	/**
	 * @param array $placeholders
	 */
	public function __construct(array $placeholders)
	{
		$this->placeholders = $placeholders;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public function compose($string)
	{
		return str_replace(array_keys($this->placeholders), array_values($this->placeholders), $string);
	}
}
