<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Mathematicator\Numbers\Entity\Fraction;
use Mathematicator\Numbers\Exception\NumberException;
use Stringable;

final class ArrayToFraction
{
	/**
	 * @param mixed[] $fraction
	 * @return Fraction
	 * @throws NumberException
	 */
	public static function convert(array $fraction): Fraction
	{
		if (!isset($fraction[0])) {
			throw new NumberException('Fraction does not have numerator!');
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
	 * @param mixed[]|string|Stringable|int|float|null $part
	 * @return Fraction|string|null
	 * @throws NumberException
	 */
	private static function convertPart($part)
	{
		if ($part === null) {
			return null;
		} elseif (is_array($part)) {
			return self::convert($part);
		} else {
			return (string) $part;
		}
	}
}
