<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Validator;


use Mathematicator\Numbers\Converter\RomanToInt;
use Mathematicator\Numbers\Exception\NumberFormatException;

/**
 * Checks whether the roman number is valid in modern set. (all positive integers and fractions /12)
 */
final class RomanNumberValidator
{
	public static function validate(string $romanNumber, bool $allowZero = true): bool
	{
		if ($romanNumber === '') {
			return false;
		}
		if ($allowZero && ($romanNumber = strtoupper($romanNumber)) === 'N') {
			return true;
		}

		try {
			RomanToInt::convert($romanNumber);
		} catch (NumberFormatException) {
			return false;
		}

		return true;
	}


	public static function isOptimal(string $romanNumber, bool $allowZero = true): bool
	{
		if ($romanNumber === '') {
			return false;
		}
		if (($normalizedInput = strtoupper($romanNumber)) === 'N' && $allowZero) {
			return true;
		}

		preg_match('/^_*/', $normalizedInput, $leadingUnderscoresMatches);

		$leadingUnderscoresCount = isset($leadingUnderscoresMatches[0])
			? strlen($leadingUnderscoresMatches[0])
			: 0;

		$regex = '';
		for ($i = $leadingUnderscoresCount; $i >= 0; $i--) {
			$regex .= self::getRegex($i);
		}

		return (bool) preg_match('/^' . $regex . '$/', $normalizedInput);
	}


	private static function getRegex($underscoreCount = 0): string
	{
		return '(_{' . $underscoreCount . '}M){0,3}((_{' . $underscoreCount . '}CM)|(_{' . $underscoreCount . '}CD)|(_{' . $underscoreCount . '}D)?(_{' . $underscoreCount . '}C){0,3})((_{' . $underscoreCount . '}XC)|(_{' . $underscoreCount . '}XL)|(_{' . $underscoreCount . '}L)?(_{' . $underscoreCount . '}X){0,3})((_{' . $underscoreCount . '}IX)|(_{' . $underscoreCount . '}IV)|(_{' . $underscoreCount . '}V)?(_{' . $underscoreCount . '}I){0,3})';
	}
}
