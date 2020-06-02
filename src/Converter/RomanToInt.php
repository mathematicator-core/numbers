<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Mathematicator\Numbers\Exception\NumberException;
use Nette\StaticClass;
use Nette\Utils\Strings;
use Stringable;

/**
 * Convert roman numerals to integer
 *
 * @see https://en.wikipedia.org/wiki/Roman_numerals
 */
final class RomanToInt
{
	use StaticClass;

	/** @var int[] */
	private static $conversionTable = [
		'M' => 1000,
		'CM' => 900,
		'D' => 500,
		'CD' => 400,
		'C' => 100,
		'XC' => 90,
		'L' => 50,
		'XL' => 40,
		'X' => 10,
		'IX' => 9,
		'V' => 5,
		'IV' => 4,
		'I' => 1,
	];


	/**
	 * @param string $romanNumber
	 * @return BigInteger
	 * @throws NumberException
	 */
	public static function convert(string $romanNumber): BigInteger
	{
		$romanNumber = Strings::upper($romanNumber);
		$romanLength = Strings::length($romanNumber);
		$return = 0;
		for ($i = 0; $i < $romanLength; $i++) {
			$convertedIntValue = self::convertSingleChar(Strings::substring($romanNumber, $i, 1));
			$nextIntValue = ($i + 1 < $romanLength) ? self::convertSingleChar(Strings::substring($romanNumber, $i + 1, 1)) : null;

			if ($nextIntValue !== null && $nextIntValue > $convertedIntValue) {
				$return += $nextIntValue - $convertedIntValue;
				$i++;
			} else {
				$return += $convertedIntValue;
			}
		}

		return BigInteger::of($return);
	}


	/**
	 * @param BigNumber|int|string|Stringable $input
	 * @return string
	 */
	public static function reverse($input): string
	{
		return IntToRoman::convert($input);
	}


	/**
	 * @param string $romanChar
	 * @return int
	 * @throws NumberException
	 */
	private static function convertSingleChar(string $romanChar): int
	{
		if (!isset(self::$conversionTable[$romanChar])) {
			NumberException::invalidInput("$romanChar");
		}

		return self::$conversionTable[$romanChar];
	}
}
