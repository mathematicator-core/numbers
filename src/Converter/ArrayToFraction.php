<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Mathematicator\Numbers\Entity\Fraction;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Nette\StaticClass;
use Stringable;

final class ArrayToFraction
{
	use StaticClass;

	/**
	 * @param mixed[] $fraction
	 * @throws NumberFormatException
	 */
	public static function convert(array $fraction): Fraction
	{
		if (!isset($fraction[0])) {
			throw new NumberFormatException('Fraction does not have numerator!');
		}

		$numeratorOut = self::convertPart($fraction[0]);
		if (isset($fraction[1])) {
			$denominatorOut = self::convertPart($fraction[1]);
		} else {
			$denominatorOut = 1;
		}

		return new Fraction($numeratorOut, $denominatorOut);
	}


	/**
	 * @return mixed[]
	 * @throws NumberFormatException
	 */
	public static function reverse(Fraction $fraction, bool $simplify = true): array
	{
		return FractionToArray::convert($fraction, $simplify);
	}


	/**
	 * @param mixed[]|string|Stringable|int|float|null $part
	 * @return Fraction|string|null
	 * @throws NumberFormatException
	 */
	private static function convertPart($part)
	{
		if ($part === null) {
			return null;
		}
		if (is_array($part)) {
			return self::convert($part);
		}

		return (string) $part;
	}
}
