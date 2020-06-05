<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Converter;


use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Mathematicator\Numbers\Exception\OutOfSetException;
use Stringable;

/**
 * Convert an integer to roman numerals
 *
 * Tip: Use validators if you want to do custom checks (e.g. not zero, or in original ancient set)
 *
 * @see https://en.wikipedia.org/wiki/Roman_numerals
 * @see https://www.wolframalpha.com/input/?i=1000000+to+roman
 * @see https://www.calculatorsoup.com/calculators/conversions/roman-numeral-converter.php
 */
final class IntToRoman extends IntToRomanBasic
{

	/**
	 * @param BigNumber|int|string|Stringable $input
	 * @return string
	 * @throws OutOfSetException
	 */
	public static function convert($input): string
	{
		$allowedSetDescription = 'integers >= 0';

		try {
			$int = BigInteger::of((string) $input);
		} catch (RoundingNecessaryException $e) {
			throw new OutOfSetException($input . ' (not integer)', $allowedSetDescription);
		}

		if ($int->isLessThan(0)) {
			throw new OutOfSetException($input . ' (negative)', $allowedSetDescription);
		}

		$out = '';

		$conversionTable = self::getConversionTable($int);

		foreach ($conversionTable as $roman => $value) {
			$matches = $int->dividedBy($value, RoundingMode::DOWN)->toInt();
			$out .= str_repeat($roman, $matches);
			$int = $int->mod($value);
		}

		return $out;
	}


	/**
	 * @param BigNumber|int|string|Stringable $input
	 * @return string
	 * @throws OutOfSetException
	 */
	public static function convertToLatex($input): string
	{
		$out = self::convert($input);

		// Get count of leading underscores (e.g. 2 for __M)
		preg_match('/^_*/', $out, $leadingUnderscoresMatches);
		$leadingUnderscoresCount = isset($leadingUnderscoresMatches[0]) ? strlen($leadingUnderscoresMatches[0]) : 0;

		// Convert underscores to latex overline
		for ($i = $leadingUnderscoresCount; $i > 0; $i--) {
			$out = (string) preg_replace('/_([IVXLCDM]|(\\\overline\{[\w{}]*\}))/', '\\overline{$1}', $out);
		}

		return $out;
	}


	/**
	 * @param BigInteger $number
	 * @return int[]
	 */
	private static function getConversionTable(BigInteger $number): array
	{
		$outTable = self::$conversionTable;
		$numberLength = strlen((string) $number);

		$numberThousands = ($numberLength - $numberLength % 3) / 3;

		for ($thousandsIterator = 1; $thousandsIterator <= $numberThousands; $thousandsIterator++) {
			$prependString = str_repeat('_', $thousandsIterator);
			foreach (self::$conversionTable as $tableLineKey => $tableLine) {
				if ($tableLineKey === 'I') {
					continue;
				}

				// Handle e.g. X => _X
				$prependedRomanNumeral = $prependString . substr((string) $tableLineKey, 0, 1);

				// Handle e.g. IX => _I_X
				if (substr((string) $tableLineKey, 1, 1)) {
					$prependedRomanNumeral .= $prependString . substr((string) $tableLineKey, 1, 1);
				}

				$outTable[$prependedRomanNumeral] = (int) $tableLine * (1000 ** $thousandsIterator);
			}
		}

		arsort($outTable);

		return $outTable;
	}
}
