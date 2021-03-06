<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;
use Mathematicator\Numbers\Entity\FractionNumbersOnly;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Mathematicator\Numbers\Helper\FractionHelper;

final class DecimalToFraction
{
	/**
	 * Converts a decimal number to the best available fraction.
	 * The fraction is automatically converted to the basic abbreviated form.
	 *
	 * @throws NumberFormatException
	 */
	public static function convert(float|int|string|Stringable|BigNumber $decimalInput): FractionNumbersOnly
	{
		$decimal = BigDecimal::of((string) $decimalInput);
		$decimalAbs = $decimal->abs();

		if ($decimal->isEqualTo(0)) {
			return new FractionNumbersOnly(0);
		}

		$numerator = $decimal->getUnscaledValue();
		$denominator = '1' . str_repeat('0', $decimalAbs->getScale());

		return FractionHelper::toShortenForm(
			new FractionNumbersOnly(
				BigDecimal::of($numerator)->multipliedBy($decimal->getSign()),
				BigDecimal::of($denominator),
			),
		);
	}
}
