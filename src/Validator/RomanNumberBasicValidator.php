<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Validator;


use Nette\StaticClass;
use Nette\Utils\Strings;

/**
 * Checks whether the number is valid in original ancient set. (integers (0), 1-3999 and fractions /12)
 */
final class RomanNumberBasicValidator
{
	use StaticClass;

	/**
	 * @param string $romanNumber
	 * @param bool $allowZero
	 * @return bool
	 * @see https://stackoverflow.com/a/267405/1044198
	 */
	public static function validate(string $romanNumber, $allowZero = false)
	{
		if (strlen($romanNumber) === 0) {
			return false;
		} elseif ($allowZero && $romanNumber === 'N') {
			return true;
		}

		return strlen($romanNumber) > 0 && (bool) preg_match('/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/', Strings::upper($romanNumber));
	}
}
