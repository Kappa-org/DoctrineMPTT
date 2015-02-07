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

namespace KappaTests\DoctrineMPTT\Utils;

use Kappa\DoctrineMPTT\Utils\StringComposer;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class StringComposerTest
 *
 * @package Kappa\DoctrineMPTT\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class StringComposerTest extends TestCase
{
	public function testCompose()
	{
		$placeholders = [
			':hello:' => "Hello world"
		];
		$composer = new StringComposer($placeholders);
		Assert::same("Hi, Hello world :some:", $composer->compose("Hi, :hello: :some:"));
	}
}

\run(new StringComposerTest());
