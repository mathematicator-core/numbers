<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Mathematicator\Numbers\Exception\OutOfSetException;
use Nette\StaticClass;
use Stringable;

/**
 * Convert integer to basic roman numerals (original ancient set)
 *
 * @see https://en.wikipedia.org/wiki/Roman_numerals
 */
class IntToRomanBasic
{
	use StaticClass;

	/** @var int[] */
	protected static $conversionTable = [
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
	 * @throws OutOfSetException
	 */
	public static function convert($input): string
	{
		try {
			$int = BigInteger::of((string) $input);
		} catch (RoundingNecessaryException $e) {
			throw new OutOfSetException($input . ' (not integer)');
		}

		// According to Wikipedia, largest valid roman number is 3999: https://en.wikipedia.org/wiki/Roman_numerals
		if ($int->isLessThan(0) || $int->isGreaterThan(3999)) {
			throw new OutOfSetException((string) $input, 'integers 0 - 3999');
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