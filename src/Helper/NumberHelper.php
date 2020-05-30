<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Helper;


use Brick\Math\BigDecimal;
use Brick\Math\Exception\RoundingNecessaryException;

class NumberHelper
{
	public static function isModuloZero(BigDecimal $a, BigDecimal $b): bool
	{
		try {
			$a->dividedBy($b)->toScale(0);
			return true;
		} catch (RoundingNecessaryException $e) {
			// Fraction cannot be evaluated to simple number directly. So go on…
			return false;
		}
	}
}
