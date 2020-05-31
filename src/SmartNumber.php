<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Mathematicator\Numbers\Converter\DecimalToFraction;
use Mathematicator\Numbers\Converter\FractionToHumanString;
use Mathematicator\Numbers\Converter\FractionToLatex;
use Mathematicator\Numbers\Entity\FractionNumbersOnly;
use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\Helper\FractionHelper;
use Mathematicator\Numbers\Helper\NumberHelper;
use Nette\SmartObject;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use RuntimeException;

/**
 * This is an implementation of an easy-to-use entity for interpreting numbers.
 *
 * The class can store the following data types:
 *
 * - Original user input
 * - Integer
 * - Decimal number with adjustable accuracy
 * - Fraction
 *
 * @property-read string $input
 * @property-read BigInteger $integer
 * @property-read float $float
 * @property-read BigDecimal $decimal
 * @property-read FractionNumbersOnly $fraction
 * @property-read string $string
 * @property-read string $latex
 * @property-read string $humanString
 * @property-write string $value
 */
final class SmartNumber
{
	use SmartObject;

	/** @var int */
	private $accuracy;

	/** @var string */
	private $input;

	/** @var BigDecimal */
	private $decimal;

	/** @var FractionNumbersOnly|null */
	private $fraction;

	/**
	 * Possible thousand separators (first is default)
	 *
	 * @var string[]|null
	 */
	private $thousandsSeparators;

	/**
	 * Possible decimal point signs (first is default)
	 *
	 * @var string[]|null
	 */
	private $decimalPointSigns;


	/**
	 * @param int|null $accuracy
	 * @param string $number number or real user input
	 * @param string[] $decimalPointSigns
	 * @param string[] $thousandsSeparators
	 * @throws NumberException
	 */
	public function __construct(?int $accuracy, string $number, array $decimalPointSigns = ['.', ','], array $thousandsSeparators = ['', ' '])
	{
		$this->accuracy = $accuracy ?? 100;
		$this->decimalPointSigns = $decimalPointSigns;
		$this->thousandsSeparators = $thousandsSeparators;
		$this->setValue($number);
	}


	/**
	 * User real input
	 *
	 * @return string
	 */
	public function getInput(): string
	{
		return $this->input;
	}


	/**
	 * @param int $roundingMode
	 * @return BigInteger
	 * @throws MathException If the number is too big and cannot be converted to a native integer.
	 */
	public function getInteger(int $roundingMode = RoundingMode::FLOOR): BigInteger
	{
		return $this->decimal->toScale(0, $roundingMode)->toBigInteger();
	}


	/**
	 * Returns stringable representation of absolute value rounded to integer.
	 *
	 * @param int $roundingMode
	 * @return int
	 * @throws MathException If the number is too big and cannot be converted to a native integer.
	 * @deprecated Use getInteger()->abs() instead.
	 */
	public function getAbsoluteInteger(int $roundingMode = RoundingMode::FLOOR): int
	{
		return $this->getInteger()->abs()->toInt();
	}


	/**
	 * WARNING! Float is only an approximation. Float data type is not precise!
	 * Always use getDecimal() method for precise computing.
	 *
	 * @return float
	 */
	public function getFloat(): float
	{
		return $this->getDecimal()->toFloat();
	}


	/**
	 * @return BigDecimal
	 */
	public function getDecimal(): BigDecimal
	{
		return $this->decimal;
	}


	/**
	 * Return float number converted to string.
	 *
	 * @return string
	 * @deprecated Use getDecimal() instead
	 */
	public function getFloatString(): string
	{
		return (string) $this->getDecimal();
	}


	/**
	 * Return number converted to fraction.
	 * For example `2.5` will be converted to `[5, 2]`.
	 * The fraction is always shortened to the basic shape.
	 *
	 * @return FractionNumbersOnly
	 */
	public function getFraction(): FractionNumbersOnly
	{
		if (!isset($this->fraction) || !$this->fraction->isValid()) {
			throw new RuntimeException('Invalid fraction: Fraction must define numerator and denominator.');
		}

		return $this->fraction;
	}


	/**
	 * Detects that the number passed is integer.
	 * Advanced methods through fractional truncation are used for detection.
	 *
	 * @return bool
	 */
	public function isInteger(): bool
	{
		try {
			$this->decimal->toScale(0);
			return true;
		} catch (RoundingNecessaryException $e) {
			return false;
		}
	}


	/**
	 * @return bool
	 */
	public function isFloat(): bool
	{
		return !$this->isInteger();
	}


	/**
	 * @return bool
	 */
	public function isPositive(): bool
	{
		return $this->decimal->isGreaterThan(0);
	}


	/**
	 * @return bool
	 */
	public function isNegative(): bool
	{
		return $this->decimal->isLessThan(0);
	}


	/**
	 * Detects that the number is zero.
	 * For very small decimal number, the function can only return approximate result.
	 *
	 * @return bool
	 */
	public function isZero(): bool
	{
		return $this->decimal->isEqualTo(0);
	}


	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->getLatex(true);
	}


	/**
	 * Returns a number in default form (in LaTeX format). Prefers fraction output.
	 *
	 * @return string
	 */
	public function getString(): string
	{
		return $this->__toString();
	}


	/**
	 * Returns a number in computer readable form (in LaTeX format).
	 *
	 * @param bool $preferFraction Returns fraction instead of decimal number if true
	 * @return string
	 */
	public function getLatex(bool $preferFraction = true): string
	{
		if ($this->isInteger()) {
			return (string) $this->getInteger();
		} elseif (!$preferFraction) {
			return (string) $this->decimal;
		}

		return (string) FractionToLatex::convert($this->getFraction());
	}


	/**
	 * Returns a number in human readable form (valid search input).
	 *
	 * @param bool $preferFraction Returns fraction instead of decimal number if true
	 * @return string
	 * @throws NumberException
	 */
	public function getHumanString(bool $preferFraction = true): string
	{
		if ($this->isInteger()) {
			return (string) $this->getInteger();
		}

		if (!$preferFraction && $this->isFloat()) {
			return (string) $this->decimal;
		}

		return FractionToHumanString::convert($this->getFraction());
	}


	/**
	 * Converts any user input to the internal state of the object.
	 * This method must be called before reading any getter, otherwise the number information will not be available.
	 *
	 * The parsing of numbers takes place in a safe way, in which the values are not distorted due to rounding.
	 * Numbers are handled like a string.
	 *
	 * @param string $value
	 * @throws NumberException
	 * @internal
	 */
	public function setValue(string $value): void
	{
		$this->input = $value;
		$value = NumberHelper::preprocessInput($value, $this->decimalPointSigns ?: [], $this->thousandsSeparators ?: []);

		if (Validators::isNumeric($value)) {
			$this->decimal = BigDecimal::of($value);
			$this->fraction = DecimalToFraction::convert($value);
		} elseif (preg_match('/^(?<mantissa>-?\d*[.]?\d+)(e|E|^)(?<exponent>-?\d*[.]?\d+)$/', $value, $parseExponential)) {
			$toString = bcmul($parseExponential['mantissa'], bcpow('10', $parseExponential['exponent'], $this->accuracy), $this->accuracy);

			if (Strings::contains($toString, '.')) {
				$floatPow = $parseExponential['mantissa'] * (10 ** $parseExponential['exponent']);
				$this->decimal = BigDecimal::of($floatPow);
				$this->fraction = DecimalToFraction::convert($floatPow);
			} else {
				$this->decimal = BigDecimal::of($toString);
				$this->fraction = new FractionNumbersOnly($toString);
			}
		} elseif (preg_match('/^(?<x>-?\d*[.]?\d+)\s*\/\s*(?<y>-?\d*[.]?\d+)$/', $value, $parseFraction)) {
			$this->fraction = FractionHelper::toShortenForm(
				new FractionNumbersOnly($parseFraction['x'], $parseFraction['y'])
			);
			$this->decimal = FractionHelper::evaluate($this->fraction, $this->accuracy, RoundingMode::FLOOR);
		} elseif (preg_match('/^([+-]{2,})(\d+.*)$/', $value, $parseOperators)) { // "---6"
			$this->setValue((substr_count($parseOperators[1], '-') % 2 === 0 ? '' : '-') . $parseOperators[2]);
		} else {
			NumberException::invalidInput($value);
		}
	}
}
