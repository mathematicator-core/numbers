<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\BigRational;
use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use InvalidArgumentException;
use Mathematicator\Numbers\Exception\UnsupportedCalcOperationException;

class Calculation
{
	private SmartNumber $number;


	public function __construct(SmartNumber $number)
	{
		$this->number = $number;
	}


	public static function of(int|float|string|BigNumber|SmartNumber $number): self
	{
		if ($number instanceof SmartNumber) {
			return new self($number);
		}

		return new self(new SmartNumber($number));
	}


	public function getResult(): SmartNumber
	{
		return $this->number;
	}


	public function __toString(): string
	{
		return (string) $this->number;
	}


	/**
	 * Returns the sum of this number and the given one.
	 *
	 * The result has a scale of `max($this->scale, $that->scale)`.
	 *
	 * @param BigNumber|int|float|string $that The number to add. Must be convertible to a BigDecimal.
	 * @throws MathException If the number is not valid, or is not convertible to a BigDecimal.
	 */
	public function plus(BigNumber|int|float|string $that): self
	{
		return self::of($this->getBigNumber()->plus($that));
	}


	/**
	 * Returns the difference of this number and the given one.
	 *
	 * The result has a scale of `max($this->scale, $that->scale)`.
	 *
	 * @param BigNumber|int|float|string $that The number to subtract. Must be convertible to a BigDecimal.
	 * @throws MathException If the number is not valid, or is not convertible to a BigDecimal.
	 */
	public function minus(BigNumber|int|float|string $that): self
	{
		return self::of($this->getBigNumber()->minus($that));
	}


	/**
	 * Returns the product of this number and the given one.
	 *
	 * The result has a scale of `$this->scale + $that->scale`.
	 *
	 * @param BigNumber|int|float|string $that The multiplier. Must be convertible to a BigDecimal.
	 * @throws MathException If the multiplier is not a valid number, or is not convertible to a BigDecimal.
	 */
	public function multipliedBy(BigNumber|int|float|string $that): self
	{
		$thisNumber = $this->getBigNumber();

		try {
			return self::of($this->getBigNumber()->multipliedBy($that));
		} catch (RoundingNecessaryException $e) {
			return self::of($thisNumber->toBigRational()->multipliedBy(BigRational::of($that)));
		}
	}


	/**
	 * Returns the result of the division of this number by the given one, at the given scale.
	 *
	 * @throws InvalidArgumentException If the scale or rounding mode is invalid.
	 * @throws MathException If the number is invalid, is zero, or rounding was necessary.
	 */
	public function dividedBy(BigNumber|int|float|string $that): self
	{
		return self::of($this->getBigNumber()->toBigRational()->dividedBy(BigRational::of($that)));
	}


	/**
	 * Returns the exact result of the division of this number by the given one.
	 *
	 * The scale of the result is automatically calculated to fit all the fraction digits.
	 *
	 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigDecimal.
	 * @throws MathException If the divisor is not a valid number, is not convertible to a BigDecimal, is zero, or the result yields an infinite number of digits.
	 */
	public function exactlyDividedBy(BigNumber|int|float|string $that): self
	{
		return self::of($this->getBigNumber()->toBigDecimal()->exactlyDividedBy($that));
	}


	/**
	 * Returns this number exponentiated to the given value.
	 *
	 * @param BigNumber|int $exponent The exponent.
	 * @param int|null $scale The desired scale, or null to use the scale of this number.
	 * @param int $roundingMode An optional rounding mode.
	 * @throws InvalidArgumentException If the exponent is not in the range 0 to 1,000,000.
	 */
	public function power(
		BigNumber|int $exponent,
		?int $scale = null,
		int $roundingMode = RoundingMode::UNNECESSARY
	): self
	{
		if ($exponent instanceof BigNumber) {
			$exponent = $exponent->toInt();
		}
		if ($exponent < 0) {
			$thisDecimal = $this->getBigNumber()->toBigDecimal();

			return self::of(
				BigDecimal::one()
					->dividedBy(
						$thisDecimal->power($exponent * -1),
						$scale,
						$roundingMode,
					),
			);
		}

		$result = $this->getBigNumber()->power($exponent);
		if ($scale !== null && $result instanceof BigDecimal) {
			$result->toScale($scale, $roundingMode);
		}

		return self::of($result);
	}


	/**
	 * Returns the quotient of the division of this number by the given one.
	 *
	 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigInteger.
	 * @throws DivisionByZeroException If the divisor is zero.
	 */
	public function quotient(BigNumber|int|float|string $that): self
	{
		return self::of($this->getBigNumber()->quotient($that));
	}


	/**
	 * Returns the remainder of the division of this number by the given one.
	 *
	 * The remainder, when non-zero, has the same sign as the dividend.
	 *
	 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigInteger.
	 * @throws DivisionByZeroException If the divisor is zero.
	 */
	public function remainder(BigNumber|int|float|string $that): self
	{
		return self::of($this->getBigNumber()->remainder($that));
	}


	/** Returns the absolute value of this number. */
	public function abs(): self
	{
		return self::of($this->getBigNumber()->abs());
	}


	/** Returns the inverse of this number. */
	public function negated(): self
	{
		return self::of($this->getBigNumber()->negated());
	}


	/**
	 * @throws UnsupportedCalcOperationException
	 */
	private function getBigNumber(): BigInteger|BigDecimal|BigRational
	{
		$number = $this->number->getNumber();

		if (
			$number instanceof BigInteger
			|| $number instanceof BigDecimal
			|| $number instanceof BigRational
		) {
			return $number;
		}

		throw new UnsupportedCalcOperationException;
	}
}
