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

	/**
	 * @param BigNumber|int|string|Stringable $input
	 * @throws OutOfSetException
	 */
	public static function convert($input): string
	{
		try {
			$int = BigInteger::of((string) $input);
		} catch (RoundingNecessaryException $e) {
			throw new OutOfSetException($input . ' (not integer)');
		}

		if ($int->isLessThan(0)) {
			throw new OutOfSetException((string) $input, 'integers >= 0');
		}

		$out = '';
		foreach (RomanToInt::getConversionTable(0) as $roman => $value) {
			$matches = $int->dividedBy($value, RoundingMode::DOWN)->toInt();
			$out .= str_repeat($roman, $matches);
			$int = $int->mod($value);
		}

		return $out;
	}


	/**
	 * @throws NumberFormatException
	 */
	public static function reverse(string $romanNumber): BigInteger
	{
		return RomanToInt::convert($romanNumber);
	}
}
