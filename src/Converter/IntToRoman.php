<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\BigRational;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Mathematicator\Numbers\Exception\OutOfRomanNumberSetException;
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

	/** @var string[] */
	private static $fractionConversionTable = [
		'·',
		'··',
		'···',
		'····',
		'·····',
		'S',
		'S·',
		'S··',
		'S···',
		'S····',
		'S·····',
	];


	/**
	 * @param BigNumber|int|string|Stringable $input
	 * @return string
	 * @throws OutOfRomanNumberSetException
	 */
	public static function convert($input): string
	{
		try {
			$integer = BigInteger::of((string) $input);
		} catch (RoundingNecessaryException $e) {
			return self::convertRationalNumber($input);
		}

		return self::convertInteger($integer);
	}


	/**
	 * @param BigNumber|int|string|Stringable $input
	 * @return string
	 * @throws OutOfRomanNumberSetException
	 * @see https://en.wikipedia.org/wiki/Roman_numerals (Fractions)
	 */
	public static function convertRationalNumber($input): string
	{
		$rationalNumber = BigRational::of((string) $input)->simplified();

		if ($rationalNumber->isLessThan('1/12')) {
			throw new OutOfRomanNumberSetException((string) $input);
		}

		$denominatorOriginal = $rationalNumber->getDenominator();

		if (in_array((string) $denominatorOriginal, ['2', '3', '4', '6', '12'], true) && $denominatorOriginal->isLessThanOrEqualTo(12)) {
			$toFinalMultiplier = BigInteger::of(12)->dividedBy($denominatorOriginal)->toInt();
			$numeratorMultiplied = $rationalNumber->getNumerator()->multipliedBy($toFinalMultiplier);

			$intFinal = $numeratorMultiplied->quotient(12);
			$numeratorFinal = $numeratorMultiplied->mod(12)->toInt();

			$out = '';

			// Integer part
			if ($intFinal->isGreaterThan(0)) {
				$out .= self::convertInteger($intFinal);
			}

			// + fraction part
			return $out . self::$fractionConversionTable[$numeratorFinal - 1];
		} else {
			throw new OutOfRomanNumberSetException((string) $input);
		}
	}


	/**
	 * @param BigInteger|int|string|Stringable $input
	 * @return string
	 * @throws OutOfRomanNumberSetException
	 */
	public static function convertInteger($input): string
	{
		try {
			$int = BigInteger::of((string) $input);
		} catch (RoundingNecessaryException $e) {
			throw new OutOfRomanNumberSetException((string) $input);
		}

		// According to Wikipedia, largest valid roman number is 3999: https://en.wikipedia.org/wiki/Roman_numerals
		if ($int->isLessThan(1) || $int->isGreaterThan(3999)) {
			throw new OutOfRomanNumberSetException((string) $input);
		}

		$out = '';

		$conversionTable = self::$conversionTable;

		foreach ($conversionTable as $roman => $value) {
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
