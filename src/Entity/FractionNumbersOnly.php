<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Entity;


use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;
use Mathematicator\Numbers\Exception\NumberFormatException;

/**
 * Entity to store simple and compound fractions
 * that consists only from numbers (no functions, variables etc.)
 */
final class FractionNumbersOnly extends Fraction
{
	public function setNumerator(int|string|Stringable|BigNumber|Fraction|FractionNumbersOnly |null $numerator): static
	{
		if ($numerator instanceof self) {
			$numerator->setParentInNumerator($this);
			$this->numerator = $numerator;
		} elseif ($numerator instanceof Fraction) {
			throw new \InvalidArgumentException(sprintf('You can set only %s for %s compound numerator.', self::class, self::class));
		} else {
			$this->numerator = BigDecimal::of((string) $numerator);
		}

		return $this;
	}


	/**
	 * @throws NumberFormatException
	 */
	public function setDenominator(int|string|Stringable|BigNumber|Fraction |null $denominator): self
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


	public function getDenominatorNotNull(): Fraction|FractionNumbersOnly|BigDecimal|string
	{
		return $this->getDenominator() ?? BigDecimal::of('1');
	}
}
