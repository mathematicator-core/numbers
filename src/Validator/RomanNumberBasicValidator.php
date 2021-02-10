<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Validator;


use Mathematicator\Numbers\Converter\RomanToInt;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Nette\Utils\Strings;

/**
 * Checks whether the number is valid in original ancient set. (integers (0), 1-3999 and fractions /12)
 */
final class RomanNumberBasicValidator
{
	/**
	 * @see https://php.vrana.cz/prevod-rimskych-cislic.php
	 */
	public static function validate(string $romanNumber, bool $allowZero = false): bool
	{
		if ($romanNumber === '') {
			return false;
		}
		if ($allowZero === true && strtoupper($romanNumber) === 'N') {
			return true;
		}
		if (preg_match('/_/', $romanNumber, $underscoresMatches) && count($underscoresMatches) > 0) {
			// Numeral with underscore (overline) is not valid in basic Roman set
			return false;
		}

		try {
			RomanToInt::convert($romanNumber);
		} catch (NumberFormatException) {
			return false;
		}

		return true;
	}


	/**
	 * @see https://stackoverflow.com/a/267405/1044198
	 */
	public static function isOptimal(string $romanNumber, bool $allowZero = false): bool
	{
		if ($romanNumber === '') {
			return false;
		}
		if ($allowZero && $romanNumber === 'N') {
			return true;
		}

		return $romanNumber !== '' && (bool) preg_match('/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/', Strings::upper($romanNumber));
	}
}
