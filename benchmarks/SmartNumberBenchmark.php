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
	 * @Revs(1000)
	 */
	public function benchCreateIntAndGetFractionNumerator()
	{
		$smartNumber = new SmartNumber(10, '158985102');
		$smartNumber->getFraction()[0]; // 158985102
	}

	/**
	 * @Revs(1000)
	 */
	public function benchCreateIntAndGetRationalNumeratorNonSimplified()
	{
		$smartNumber = new SmartNumber(10, '158985102');
		$smartNumber->getRational()->getNumerator(false); // 158985102
	}

	/**
	 * @Revs(1000)
	 */
	public function benchCreateIntAndGetRationalNumeratorSimplified()
	{
		$smartNumber = new SmartNumber(10, '158985102');
		$smartNumber->getRational()->getNumerator(true); // 158985102
	}

}
