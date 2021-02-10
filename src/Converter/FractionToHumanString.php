<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Mathematicator\Numbers\Entity\Fraction;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Mathematicator\Numbers\HumanString\MathHumanStringBuilder;
use Mathematicator\Numbers\HumanString\MathHumanStringToolkit;

final class FractionToHumanString
{
	/**
	 * @param bool $simplify Remove denominator if === 1
	 * @throws NumberFormatException
	 */
	public static function convert(Fraction $fraction, bool $simplify = true): MathHumanStringBuilder
	{
		if (!$fraction->isValid()) {
			throw new NumberFormatException('Fraction is not valid!');
		}

		$numeratorString = self::convertPart($fraction->getNumerator(), false, $simplify);
		$denominatorString = self::convertPart($fraction->getDenominator(), true, $simplify);

		return MathHumanStringToolkit::frac($numeratorString, $denominatorString);
	}


	/**
	 * @param bool $simplify Remove denominator if === 1
	 */
	private static function convertPart(Fraction|string|null $part, bool $isDenominator, bool $simplify): string
	{
		if ($isDenominator && $part === null) {
			if (!$simplify) {
				return '1';
			}

			return '';
		}
		if ($part instanceof Fraction) {
			return (string) MathHumanStringToolkit::wrap(self::convert($part, $simplify), '(', ')');
		}

		return (string) $part;
	}
}
