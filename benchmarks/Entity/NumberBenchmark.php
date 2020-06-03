<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Benchmarks;

use Mathematicator\Numbers\Entity\Number;

/**
 * @Iterations(5)
 */
class NumberBenchmark
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
		$smartNumber = new Number('158985102');
	}


	/**
	 * @Revs(1000)
	 */
	public function benchCreateIntAndGetFractionNumerator()
	{
		$smartNumber = new Number('158985102');
		$smartNumber->getFraction()[0]; // 158985102
	}


	/**
	 * @Revs(1000)
	 */
	public function benchCreateIntAndGetRationalNumeratorNonSimplified()
	{
		$smartNumber = new Number('1482002/10');
		$smartNumber->getRational(false)->getNumerator(); // 1482002
	}


	/**
	 * @Revs(1000)
	 */
	public function benchCreateIntAndGetRationalNumeratorSimplified()
	{
		$smartNumber = new Number('158985102/10');
		$smartNumber->getRational(true)->getNumerator(); // 741001
	}
}
