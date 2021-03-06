<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Helper;


use Brick\Math\BigDecimal;
use Brick\Math\Exception\RoundingNecessaryException;
use InvalidArgumentException;

class NumberHelper
{
	public static function isModuloZero(BigDecimal $a, BigDecimal $b): bool
	{
		try {
			$a->dividedBy($b)->toScale(0);

			return true;
		} catch (RoundingNecessaryException) {
			// Fraction cannot be evaluated to simple number directly. So go on…
			return false;
		}
	}


	/** Removes unnecessary whitespaces in number string. */
	public static function removeSpaces(string $input): string
	{
		return trim((string) preg_replace('/(\d)\s+(\d)/', '$1$2', $input));
	}


	/** Removes trailing zeros from decimals. E.g. 250.0000 results in 250 */
	public static function removeTrailingZeros(string $input): string
	{
		$input = (string) preg_replace('/^([+-]*)(\d*\.\d*?)0+$/', '$1$2', $input);

		return self::removeTrailingDot($input);
	}


	/** Removes trailing dot. E.g. 265. results in 265 */
	public static function removeTrailingDot(string $input): string
	{
		return rtrim($input, '.');
	}


	/**
	 * Preprocess user input for further processing
	 *
	 * @param string[] $decimalPointSigns Default is a dot "." only
	 * @param string[] $thousandsSeparators
	 */
	public static function preprocessInput(
		string $input,
		array $decimalPointSigns = [],
		array $thousandsSeparators = []
	): string {
		// Check parameters validity
		if (count(array_intersect($decimalPointSigns, $thousandsSeparators))) {
			throw new InvalidArgumentException('Decimal point signs and thousands separators have to be unique.');
		}
		if (count(array_intersect(
			array_merge($decimalPointSigns, $thousandsSeparators),
			['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '/'],
		))) {
			throw new InvalidArgumentException('Decimal point signs nor thousands separators cannot contain number nor "/" sign.');
		}

		$input = self::removeSpaces($input);
		$input = (string) str_replace($thousandsSeparators, '', $input);
		$input = (string) str_replace($decimalPointSigns, '.', $input);
		$input = self::removeTrailingZeros($input);
		return self::removeWrapBrackets($input);
	}


	public static function removeWrapBrackets(string $input): string
	{
		return (string) preg_replace('/^(\()(.*)(\))$/', '$2', $input);
	}
}
