<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\BigRational;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Mathematicator\Numbers\Converter\RationalToHumanString;
use Mathematicator\Numbers\Converter\RationalToLatex;
use Mathematicator\Numbers\Entity\FractionNumbersOnly;
use Mathematicator\Numbers\Exception\NumberException;
use Mathematicator\Numbers\Helper\NumberHelper;
use Mathematicator\Numbers\HumanString\MathHumanStringBuilder;
use Mathematicator\Numbers\HumanString\MathHumanStringToolkit;
use Mathematicator\Numbers\Latex\MathLatexBuilder;
use Mathematicator\Numbers\Latex\MathLatexToolkit;
use Nette\SmartObject;

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
 * @property-read BigRational $rational
 * @property-read string $string
 * @property-read MathLatexBuilder $latex
 * @property-read MathHumanStringBuilder $humanString
 */
final class SmartNumber
{
	use SmartObject;

	/**
	 * Original user input
	 * @var string
	 */
	private $input;

	/**
	 * Number main storage
	 * @var BigNumber
	 */
	private $number;

	/** @var mixed[] */
	private $cache = [];


	/**
	 * @param string $number Number in string.
	 * Allowed formats are: 123456789, 12345.6789, 5/8
	 * If you have a real user input in nonstandard format, please NumberHelper::preprocessInput method first
	 * @throws NumberException
	 */
	public function __construct(string $number)
	{
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
		return $this->number->toScale(0, $roundingMode)->toBigInteger();
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
	 * @param int $rationalScaleLimit Limit scale if rounding is needed (rational numbers). Default: 10
	 * @param int $rationalRoundingMode Rounding mode for rational numbers
	 * @return float
	 */
	public function getFloat(int $rationalScaleLimit = 10, int $rationalRoundingMode = RoundingMode::FLOOR): float
	{
		$cacheKey = $rationalScaleLimit . '_' . $rationalRoundingMode;
		if (isset($this->cache['float'][$cacheKey])) {
			return $this->cache['float'][$cacheKey];
		} else {
			return $this->cache['float'][$cacheKey] = $this->getDecimal($rationalScaleLimit, $rationalRoundingMode)->toFloat();
		}
	}


	/**
	 * @param int $rationalScaleLimit Limit scale if rounding is needed (rational numbers). Default: 10
	 * @param int $rationalRoundingMode Rounding mode for rational numbers
	 * @return BigDecimal
	 */
	public function getDecimal(int $rationalScaleLimit = 10, int $rationalRoundingMode = RoundingMode::FLOOR): BigDecimal
	{
		$cacheKey = $rationalScaleLimit . '_' . $rationalRoundingMode;
		if (isset($this->cache['decimal'][$cacheKey])) {
			return $this->cache['decimal'][$cacheKey];
		} else {
			if ($this->number instanceof BigRational) {
				$result = (string) $this->number->getNumerator()->toBigDecimal()
					->dividedBy($this->number->getDenominator(), $rationalScaleLimit, $rationalRoundingMode);
				return BigDecimal::of(NumberHelper::removeTrailingZeros($result));
			}

			return $this->cache['decimal'][$cacheKey] = $this->number->toBigDecimal();
		}
	}


	/**
	 * Return float number converted to string.
	 *
	 * @param int $rationalScaleLimit Limit scale if rounding is needed (rational numbers). Default: 10
	 * @param int $rationalRoundingMode Rounding mode for rational numbers
	 * @return string
	 * @deprecated Use getDecimal() instead
	 */
	public function getFloatString(int $rationalScaleLimit = 10, int $rationalRoundingMode = RoundingMode::FLOOR): string
	{
		return (string) $this->getDecimal($rationalScaleLimit, $rationalRoundingMode);
	}


	/**
	 * Return number converted to fraction.
	 * For example `2.5` will be converted to `[5, 2]`.
	 * The fraction is always shortened to the basic shape.
	 * TIP: Use getRational() method instead for faster first result (limited functionality)
	 *
	 * @param bool $simplify Simplify fraction on output (null means to not simplify rational input, else simplify)
	 * @return FractionNumbersOnly
	 */
	public function getFraction(?bool $simplify = null): FractionNumbersOnly
	{
		$simplify = ($simplify === true || ($simplify === null && !($this->number instanceof BigRational)));

		if ($this->cache[$simplify ? 'fractionSimplified' : 'fraction']) {
			return clone $this->cache[$simplify ? 'fractionSimplified' : 'fraction'];
		}

		$rationalNumber = $this->getRational($simplify);
		return clone($this->cache[$simplify ? 'fractionSimplified' : 'fraction'] = new FractionNumbersOnly($rationalNumber->getNumerator(), $rationalNumber->getDenominator()));
	}


	/**
	 * Returns simple rational number (similar to getFraction() but
	 * without ArrayAccess and advance features).
	 * TIP: Use getRational(false) for faster first result (returns not simplified rational number)
	 *
	 * @param bool|null $simplify Simplify rational number output (null means to not simplify rational input, else simplify)
	 * @return BigRational
	 */
	public function getRational(?bool $simplify = null): BigRational
	{
		$simplify = ($simplify === true || ($simplify === null && !($this->number instanceof BigRational)));

		if ($simplify) {
			return $this->getRationalSimplified();
		}

		if ($this->cache['rational']) {
			return $this->cache['rational'];
		} elseif ($this->number instanceof BigRational) {
			return $this->cache['rational'] = $this->number;
		} else {
			return $this->cache['rational'] = $this->number->toBigRational();
		}
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
			$this->number->toScale(0);
			return true;
		} catch (RoundingNecessaryException $e) {
		}
		return false;
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
		return $this->number->isGreaterThan(0);
	}


	/**
	 * @return bool
	 */
	public function isNegative(): bool
	{
		return $this->number->isLessThan(0);
	}


	/**
	 * Detects that the number is zero.
	 * For very small decimal number, the function can only return approximate result.
	 *
	 * @return bool
	 */
	public function isZero(): bool
	{
		return $this->number->isEqualTo(0);
	}


	/**
	 * Returns number represented by string (valid SmartNumber input)
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		return (string) $this->getHumanString();
	}


	/**
	 * Returns a number in default form (in LaTeX format). Prefers fraction output.
	 *
	 * @return string
	 */
	public function getString(): string
	{
		return (string) $this;
	}


	/**
	 * Returns a number in computer readable form (in LaTeX format).
	 *
	 * @return MathLatexBuilder
	 */
	public function getLatex(): MathLatexBuilder
	{
		if ($this->cache['latex'] !== null) {
			return $this->cache['latex'];
		} elseif ($this->number instanceof BigRational) {
			return $this->cache['latex'] = RationalToLatex::convert($this->getRational(false));
		} elseif ($this->number instanceof BigDecimal) {
			return $this->cache['latex'] = MathLatexToolkit::create((string) $this->number);
		} else {
			return $this->cache['latex'] = MathLatexToolkit::create((string) $this->number);
		}
	}


	/**
	 * Returns a number in human readable form (valid SmartNumber input).
	 *
	 * @return MathHumanStringBuilder
	 */
	public function getHumanString(): MathHumanStringBuilder
	{
		if ($this->cache['humanString'] !== null) {
			return $this->cache['humanString'];
		} elseif ($this->number instanceof BigRational) {
			return $this->cache['humanString'] = RationalToHumanString::convert($this->getRational(false));
		} else {
			return $this->cache['humanString'] = MathHumanStringToolkit::create((string) $this->number);
		}
	}


	private function getRationalSimplified(): BigRational
	{
		if ($this->cache['rationalSimplified']) {
			return $this->cache['rationalSimplified'];
		} elseif ($this->number instanceof BigRational) {
			return $this->cache['rationalSimplified'] = $this->number->simplified();
		} else {
			return $this->cache['rationalSimplified'] = $this->number->toBigRational()->simplified();
		}
	}


	/**
	 * Converts any user input to the internal state of the object.
	 * This method must be called before reading any getter, otherwise the number information will not be available.
	 *
	 * The parsing of numbers takes place in a safe way, in which the values are not distorted due to rounding.
	 * Numbers are handled like a string.
	 *
	 * @param string $input
	 * @throws NumberException
	 */
	private function setValue(string $input): void
	{
		$this->invalidateCache(); // Defines array cache indexes
		$this->input = $input;

		try {
			$this->setValueDirectly($input);
			return;
		} catch (NumberFormatException $e) {
		}

		$input = NumberHelper::preprocessInput($input, ['.'], ['', ' ']);

		try {
			$this->setValueDirectly($input);
			return;
		} catch (NumberFormatException $e) {
		}

		// Solve multiple positivity signs (e.g. --6 => 6, ---5 => -5, --5.2 => 5.2, --5/2 => 5/2)
		if (preg_match('/^([+-]{2,})(.*)$/', $input, $parseResult)) {
			$this->setValue((substr_count($parseResult[1], '-') % 2 === 0 ? '' : '-') . $parseResult[2]);
			return;
		}

		// Solve fraction with decimals
		if (preg_match('/^(\d*\.\d*)\/(\d*\.\d*)$/', $input, $parseResult)) {
			$numerator = BigDecimal::of($parseResult[1]);
			$denominator = BigDecimal::of($parseResult[2]);

			$multiplier = 10 * (($numerator->getScale() > $denominator->getScale()) ? $numerator->getScale() : $denominator->getScale());

			$this->number = BigRational::nd($parseResult[1] * $multiplier, $parseResult[2] * $multiplier);
			return;
		}

		NumberException::invalidInput($input);
	}


	/**
	 * @param string $input
	 * @throws NumberFormatException
	 */
	private function setValueDirectly(string $input): void
	{
		$this->number = BigNumber::of($input);
	}


	/**
	 * Invalidates internal cache used for faster reading.
	 */
	private function invalidateCache(): void
	{
		$this->cache = [
			'float' => null,
			'fraction' => null,
			'fractionSimplified' => null,
			'humanString' => null,
			'latex' => null,
			'rational' => null,
			'rationalSimplified' => null,
		];
	}
}
