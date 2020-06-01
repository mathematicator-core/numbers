<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Benchmarks;

use Mathematicator\Numbers\SmartNumber;

/**
 * @Iterations(5)
 */
class SmartNumberBenchmark
{

	/**
	 * Only for comparison purposes
	 *
	 * @Revs(1000)
	 */
	public function benchAssignIntToStringPhp()
	{
		$smartNumber = (string) 158985102;
	}

	/**
	 * @Revs(1000)
	 */
	public function benchCreateInt()
	{
		$smartNumber = new SmartNumber(10, '158985102');
	}

	/**
	 * @Revs(1)
	 */
	public function benchCreateIntAndGetFraction()
	{
		$smartNumber = new SmartNumber(10, '158985102');
		$smartNumber->getFraction()[0]; // 158985102
	}

}
