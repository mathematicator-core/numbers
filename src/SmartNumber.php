<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
use Brick\Math\RoundingMode;
use Mathematicator\Numbers\Converter\DecimalToFraction;
use Mathematicator\Numbers\Converter\FractionToHumanString;
use Mathematicator\Numbers\Converter\FractionToLatex;
use Mathematicator\Numbers\Entity\FractionNumbersOnly;
use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\Helper\FractionHelper;
use Nette\SmartObject;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use RuntimeException;

/**
 * This is an implementation of an easy-to-use entity for interpreting numbers.
 *
 * The service supports the storage of the following data types:
 *
 * - Original user input
 * - Integer
 * - Decimal number with adjustable accuracy
 * - Fraction
 *
 * Decimal numbers are automatically converted to a fraction when entered.
 * WARNING: Always use fractions for calculations to avoid problems with rounding of intermediate calculations!
 */
final class SmartNumber
{
	use SmartObject;

	/** @var int */
	private $accuracy;

	/** @var string */
	private $input;

	/** @var string */
	private $string;

	/** @var BigInteger */
	private $integer;

	/** @var BigDecimal */
	private $decimal;

	/** @var FractionNumbersOnly|null */
	private $fraction;


	/**
	 * @param int|null $accuracy
	 * @param string $number number or real user input
	 * @throws NumberException
	 */
	public function __construct(?int $accuracy, string $number)
	{
		$this->accuracy = $accuracy ?? 100;
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
	 * This service represent integer as a string to avoid precision distortion.
	 *
	 * @return string
	 */
	public function getInteger(): string
	{
		return (string) $this->integer;
	}


	/**
	 * @return int
	 */
	public function getAbsoluteInteger(): int
	{
		return $this->integer->abs()->toInt();
	}


	/**
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
	 */
	public function getFloatString(): string
	{
		return (string) $this->getFloat();
	}


	/**
	 * Return number converted to fraction.
	 * For example `2.5` will be converted to `[5, 2]`.
	 * The fraction is always shortened to the basic shape.
	 *
	 * @return FractionNumbersOnly
	 */
	public function getFraction()
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
		return $this->integer !== null && ($this->input === (string) $this->integer || $this->getFraction()[1] === '1');
	}


	/**
	 * @return bool
	 */
	public function isFloat(): bool
	{
		return !$this->isInteger() && $this->integer !== null;
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
		return $this->getString();
	}


	/**
	 * Returns a number in default form (in LaTeX format). Prefers fraction output.
	 *
	 * @return string
	 */
	public function getString(): string
	{
		return $this->getLatex(true);
	}


	/**
	 * Returns a number in computer readable form (in LaTeX format).
	 *
	 * @param bool $preferFraction
	 * @return string
	 */
	public function getLatex(bool $preferFraction = false): string
	{
		if ($this->isInteger()) {
			return (string) $this->integer;
		}

		if (!$preferFraction && $this->isFloat()) {
			return (string) $this->decimal;
		}

		return (string) FractionToLatex::convert($this->getFraction());
	}


	/**
	 * Returns a number in human readable form (valid search input).
	 *
	 * @return string
	 * @throws NumberException
	 */
	public function getHumanString(): string
	{
		if ($this->isInteger()) {
			return (string) $this->integer;
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
		// Preprocess input
		$value = (string) preg_replace('/(\d)\s+(\d)/', '$1$2', $value);
		$value = rtrim((string) preg_replace('/^(\d*\.\d*?)0+$/', '$1', $value), '.');

		// Store input
		$this->input = $value;

		if (Validators::isNumeric($value)) {
			$toInteger = (string) preg_replace('/\..*$/', '', $value);
			$this->integer = BigInteger::of($toInteger);

			if (Validators::isNumericInt($value)) {
				$this->decimal = BigDecimal::of($toInteger);
				$this->setStringHelper($toInteger);
				$this->fraction = new FractionNumbersOnly($toInteger, 1);
			} else {
				$this->decimal = BigDecimal::of($value);
				$this->setStringHelper($value);
				$this->fraction = DecimalToFraction::convert($value);
			}
		} elseif (preg_match('/^(?<mantissa>-?\d*[.]?\d+)(e|E|^)(?<exponent>-?\d*[.]?\d+)$/', $value, $parseExponential)) {
			$toString = bcmul($parseExponential['mantissa'], bcpow('10', $parseExponential['exponent'], $this->accuracy), $this->accuracy);
			$this->setStringHelper($toString);
			if (Strings::contains($toString, '.')) {
				$floatPow = $parseExponential['mantissa'] * (10 ** $parseExponential['exponent']);
				$this->integer = BigInteger::of((string) preg_replace('/\..+$/', '', $toString));
				$this->decimal = BigDecimal::of($floatPow);
				$this->fraction = DecimalToFraction::convert($floatPow);
			} else {
				$this->integer = BigInteger::of($toString);
				$this->decimal = BigDecimal::of($toString);
				$this->fraction = new FractionNumbersOnly($toString);
			}
		} elseif (preg_match('/^(?<x>-?\d*[.]?\d+)\s*\/\s*(?<y>-?\d*[.]?\d+)$/', $value, $parseFraction)) {
			$this->fraction = FractionHelper::toShortenForm(
				new FractionNumbersOnly($parseFraction['x'], $parseFraction['y'])
			);
			$this->decimal = FractionHelper::evaluate($this->fraction, $this->accuracy, RoundingMode::FLOOR);
			$this->integer = $this->decimal->toBigInteger();
			$this->setStringHelper((string) $this->decimal);
		} elseif (preg_match('/^([+-]{2,})(\d+.*)$/', $value, $parseOperators)) { // "---6"
			$this->setValue((substr_count($parseOperators[1], '-') % 2 === 0 ? '' : '-') . $parseOperators[2]);
		} else {
			NumberException::invalidInput($value);
		}
	}


	/**
	 * @param string $string
	 */
	private function setStringHelper(string $string): void
	{
		$this->string = $string;

		if (preg_match('/^(?<int>.*)(\.|\,)(?<float>.+?)0+$/', $string, $redundantZeros)) {
			$this->string = $redundantZeros['int'] . '.' . $redundantZeros['float'];
		}
	}
}
