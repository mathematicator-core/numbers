<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Mathematicator\Numbers\Entity\Fraction;
use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\HumanString\MathHumanStringBuilder;
use Mathematicator\Numbers\HumanString\MathHumanStringToolkit;

final class FractionToHumanString
{
	/**
	 * @param Fraction $fraction
	 * @param bool $simplify Remove denominator if === 1
	 * @return MathHumanStringBuilder
	 * @throws NumberException
	 */
	public static function convert(Fraction $fraction, bool $simplify = true): MathHumanStringBuilder
	{
		if (!$fraction->isValid()) {
			throw new NumberException('Fraction is not valid!');
		}

		$numeratorString = self::convertPart($fraction->getNumerator(), false, $simplify);
		$denominatorString = self::convertPart($fraction->getDenominator(), true, $simplify);

		return MathHumanStringToolkit::frac($numeratorString, $denominatorString);
	}


	/**
	 * @param Fraction|string|null $part
	 * @param bool $isDenominator
	 * @param bool $simplify Remove denominator if === 1
	 * @return string
	 * @throws NumberException
	 */
	private static function convertPart($part, bool $isDenominator, bool $simplify)
	{
		if ($isDenominator && $part === null) {
			if (!$simplify) {
				return '1';
			}
			return '';
		} elseif ($part instanceof Fraction) {
			return (string) MathHumanStringToolkit::wrap(self::convert($part, $simplify), '(', ')');
		} else {
			return (string) $part;
		}
	}
}
