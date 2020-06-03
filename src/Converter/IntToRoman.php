<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Nette\StaticClass;
use Stringable;

/**
 * Convert integer to roman numerals
 *
 * @see https://en.wikipedia.org/wiki/Roman_numerals
 */
final class IntToRoman
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
	 * @param BigNumber|int|string|Stringable $input
	 * @return string
	 */
	public static function convert($input): string
	{
		$int = BigInteger::of((string) $input);
		$out = '';

		foreach (self::$conversionTable as $roman => $value) {
			$matches = $int->dividedBy($value, RoundingMode::DOWN)->toInt();
			$out .= str_repeat($roman, $matches);
			$int = $int->mod($value);
		}

		return $out;
	}


	/**
	 * @param string $romanNumber
	 * @return BigInteger
	 * @throws NumberFormatException
	 */
	public static function reverse($romanNumber): BigInteger
	{
		return RomanToInt::convert($romanNumber);
	}
}
