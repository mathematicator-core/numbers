<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Mathematicator\Numbers\Entity\Fraction;
use Mathematicator\Numbers\Exception\NumberFormatException;

final class FractionToArray
{
	/**
	 * @return mixed[]
	 * @throws NumberFormatException
	 */
	public static function convert(Fraction $fraction, bool $simplify = true): array
	{
		if (!$fraction->isValid()) {
			throw new NumberFormatException('Fraction is not valid!');
		}

		return [
			self::convertPart($fraction->getNumerator(), false, $simplify),
			self::convertPart($fraction->getDenominator(), true, $simplify),
		];
	}


	/**
	 * @param mixed[] $fraction
	 * @throws NumberFormatException
	 */
	public static function reverse(array $fraction): Fraction
	{
		return ArrayToFraction::convert($fraction);
	}


	/**
	 * @return string|mixed[]|null
	 * @throws NumberFormatException
	 */
	private static function convertPart(
		Fraction|string |null $part,
		bool $isDenominator,
		bool $simplify
	): string|array|null {
		if ($isDenominator && $part === null) {
			if (!$simplify) {
				return '1';
			}

			return null;
		}
		if ($part instanceof Fraction) {
			return self::convert($part, $simplify);
		}

		return (string) $part;
	}
}
