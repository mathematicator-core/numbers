<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Entity;


use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;
use Mathematicator\Numbers\Converter\FractionToHumanString;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Stringable;

/**
 * Entity to store simple and compound fractions
 *
 * @implements ArrayAccess<int, mixed[]|string|null>
 */
class Fraction
{
	use FractionArrayAccessTrait;

	protected Fraction|BigDecimal|string|self |null $numerator;

	protected Fraction|BigDecimal|string|self |null $denominator;

	/** Superior fraction (if in compound structure) */
	protected ?Fraction $parentInNumerator = null;

	/** Superior fraction (if in compound structure) */
	protected ?Fraction $parentInDenominator = null;


	public function __construct(
		int|string|Stringable|BigNumber|Fraction |null $numerator = null,
		int|string|Stringable|BigNumber|Fraction |null $denominator = null
	) {
		if ($numerator !== null) {
			$this->setNumerator($numerator);
		}
		if ($denominator !== null || $numerator !== null) {
			$this->setDenominator($denominator ?? 1);
		}
	}


	public function __clone()
	{
		if (is_object($this->numerator)) {
			$this->numerator = clone $this->numerator;
		}
		if (is_object($this->denominator)) {
			$this->denominator = clone $this->denominator;
		}
		if (is_object($this->parentInNumerator)) {
			$this->parentInNumerator = clone $this->parentInNumerator;
		}
		if (is_object($this->parentInDenominator)) {
			$this->parentInDenominator = clone $this->parentInDenominator;
		}
	}


	/**
	 * Returns a human string (e.g. (5/2)/1).
	 *
	 * @throws NumberFormatException
	 */
	public function __toString(): string
	{
		return (string) FractionToHumanString::convert($this);
	}


	/** Checks whether the fraction is valid for further computing.  */
	public function isValid(): bool
	{
		return $this->numerator !== null;
	}


	public function getNumerator(): Fraction|BigDecimal|string|self |null
	{
		return $this->numerator;
	}


	public function setNumerator(int|string|Stringable|BigNumber|Fraction |null $numerator): static
	{
		if ($numerator instanceof self) {
			$numerator->setParentInNumerator($this);
			$this->numerator = $numerator;
		} elseif ($numerator === null) {
			$this->numerator = null;
		} else {
			$this->numerator = (string) $numerator;
		}

		return $this;
	}


	public function getDenominator(): Fraction|BigDecimal|string|self |null
	{
		return $this->denominator;
	}


	public function setDenominator(int|string|Stringable|BigNumber|Fraction |null $denominator): self
	{
		if ($denominator instanceof self) {
			$denominator->setParentInDenominator($this);
			$this->denominator = $denominator;
		} elseif ($denominator === null) {
			$this->denominator = null;
		} else {
			$this->denominator = (string) $denominator;
		}

		return $this;
	}


	public function getDenominatorNotNull(): Fraction|FractionNumbersOnly|BigDecimal|string
	{
		return $this->getDenominator() ?: '1';
	}


	public function getParent(): ?self
	{
		return $this->parentInNumerator ?: $this->parentInDenominator;
	}


	public function setParentInNumerator(?self $parentInNumerator): self
	{
		$this->parentInDenominator = null;
		$this->parentInNumerator = $parentInNumerator;

		return $this;
	}


	public function setParentInDenominator(?self $parentInDenominator): self
	{
		$this->parentInNumerator = null;
		$this->parentInDenominator = $parentInDenominator;

		return $this;
	}
}
