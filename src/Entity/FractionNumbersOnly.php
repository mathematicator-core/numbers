<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Entity;


use ArrayAccess;
use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Stringable;

/**
 * Entity to store simple and compound fractions
 * that consists only from numbers (no functions, variables etc.)
 *
 * @implements ArrayAccess<int, mixed[]|string|null>
 */
final class FractionNumbersOnly extends Fraction
{
	protected self|BigDecimal |null $numerator;

	protected self|BigDecimal |null $denominator;


	public function __construct(
		int|string|Stringable|BigNumber|FractionNumbersOnly |null $numerator = null,
		int|string|Stringable|BigNumber|FractionNumbersOnly |null $denominator = null
	) {
		parent::__construct($numerator, $denominator);
	}


	public function getNumerator(): FractionNumbersOnly|BigDecimal|null
	{
		return $this->numerator;
	}


	/**
	 * @throws NumberFormatException
	 */
	public function setNumerator(int|string|Stringable|BigNumber|FractionNumbersOnly |null $numerator)
	{
		if ($numerator instanceof self) {
			$numerator->setParentInNumerator($this);
			$this->numerator = $numerator;
		} elseif ($numerator instanceof Fraction) {
			throw new NumberFormatException(sprintf('You can set only %s for %s compound numerator.', self::class, self::class));
		} else {
			$this->numerator = BigDecimal::of((string) $numerator);
		}

		return $this;
	}


	public function getDenominator(): FractionNumbersOnly|BigDecimal|null
	{
		return $this->denominator;
	}


	/**
	 * @throws NumberFormatException
	 */
	public function setDenominator(int|string|Stringable|BigNumber|Fraction $denominator): self
	{
		if ($denominator instanceof self) {
			$denominator->setParentInDenominator($this);
			$this->denominator = $denominator;
		} elseif ($denominator instanceof Fraction) {
			throw new NumberFormatException(sprintf('You can set only %s for %s compound denominator.', self::class, self::class));
		} else {
			$this->denominator = BigDecimal::of((string) $denominator);
		}

		return $this;
	}


	public function getDenominatorNotNull(): FractionNumbersOnly|BigDecimal
	{
		return $this->getDenominator() ?? BigDecimal::of('1');
	}
}
