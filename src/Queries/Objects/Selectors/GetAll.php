<?php
/**
 * This file is part of the Kappa\DoctrineMPTT package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\DoctrineMPTT\Queries\Objects\Selectors;

use Kappa\DoctrineMPTT\Configurator;
use Kappa\DoctrineMPTT\Utils\StringComposer;
use Kdyby;
use Kdyby\Doctrine\QueryObject;

/**
 * Class GetAll
 *
 * @package Kappa\DoctrineMPTT\Queries\Objects\Selectors
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class GetAll extends QueryObject
{
	/** @var Configurator */
	private $configurator;

	/**
	 * @param Configurator $configurator
	 */
	public function __construct(Configurator $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * @param \Kdyby\Persistence\Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository)
	{
		$stringComposer = new StringComposer([
			':leftName:' => $this->configurator->get(Configurator::LEFT_NAME)
		]);
		return $repository->createQueryBuilder('e')
			->select('e')
			->orderBy($stringComposer->compose('e.:leftName:'), 'ASC');
	}
}
