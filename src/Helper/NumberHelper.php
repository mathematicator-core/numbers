<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Helper;


use Brick\Math\BigDecimal;
use Brick\Math\Exception\RoundingNecessaryException;

class NumberHelper
{
	public static function isModuloZero(BigDecimal $a, BigDecimal $b): bool
	{
		try {
			$a->dividedBy($b)->toScale(0);
			return true;
		} catch (RoundingNecessaryException $e) {
			// Fraction cannot be evaluated to simple number directly. So go on…
			return false;
		}
	}


	/**
	 * Removes unnecessary whitespaces in number string.
	 *
	 * @param string $input
	 * @return string
	 */
	public static function removeSpaces(string $input): string
	{
		return trim((string) preg_replace('/(\d)\s+(\d)/', '$1$2', $input));
	}


	/**
	 * Removes trailing zeros from decimals. E.g. 250.0000 results in 250
	 *
	 * @param string $input
	 * @return string
	 */
	public static function removeTrailingZeros(string $input): string
	{
		$input = (string) preg_replace('/^(\d*\.\d*?)0+$/', '$1', $input);
		return self::removeTrailingDot($input);
	}


	/**
	 * Removes trailing dot. E.g. 265. results in 265
	 *
	 * @param string $input
	 * @return string
	 */
	public static function removeTrailingDot(string $input): string
	{
		return rtrim($input, '.');
	}


	/**
	 * Preprocess user input for further processing
	 *
	 * @param string $input
	 * @return string
	 */
	public static function preprocessInput(string $input): string
	{
		$input = self::removeSpaces($input);
		$input = self::removeTrailingZeros($input);
		return $input;
	}
}
