<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Brick\Math\BigRational;
use Mathematicator\Numbers\HumanString\MathHumanStringBuilder;
use Mathematicator\Numbers\HumanString\MathHumanStringToolkit;

final class RationalToHumanString
{
	public static function convert(BigRational $rationalNumber): MathHumanStringBuilder
	{
		return MathHumanStringToolkit::frac(
			(string) $rationalNumber->getNumerator(),
			(string) $rationalNumber->getDenominator(),
		);
	}
}
