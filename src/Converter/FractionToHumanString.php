<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Mathematicator\Numbers\Entity\Fraction;
use Mathematicator\Numbers\Exception\NumberException;

final class FractionToHumanString
{
	/**
	 * @param Fraction $fraction
	 * @param bool $simplify
	 * @return string
	 * @throws NumberException
	 */
	public static function convert(Fraction $fraction, bool $simplify = true): string
	{
		if (!$fraction->isValid()) {
			throw new NumberException('Fraction is not valid!');
		}

		$numeratorString = self::convertPart($fraction->getNumerator(), false, $simplify);
		$denominatorString = self::convertPart($fraction->getDenominator(), true, $simplify);

		return $numeratorString . '/' . $denominatorString;
	}


	/**
	 * @param Fraction|string|null $part
	 * @param bool $isDenominator
	 * @param bool $simplify
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
			return '(' . self::convert($part, $simplify) . ')';
		} else {
			return (string) $part;
		}
	}
}