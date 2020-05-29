<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Entity;

use Brick\Math\BigNumber;
use Stringable;

/**
 * Entity to store simple and compound fractions
 */
class Fraction
{
	/** @var Fraction|null */
	protected $numeratorFraction;
	/** @var string|null */
	protected $numeratorString;
	/** @var Fraction|null */
	protected $denominatorFraction;
	/** @var string|null */
	protected $denominatorString;
	/**
	 * Superior fraction (if in compound structure)
	 *
	 * @var Fraction|null
	 */
	protected $parentInNumerator;
	/**
	 * Superior fraction (if in compound structure)
	 *
	 * @var Fraction|null
	 */
	protected $parentInDenominator;


	/**
	 * @param string|Stringable|BigNumber|Fraction|null $numerator optional
	 * @param string|Stringable|BigNumber|Fraction|null $denominator optional
	 */
	public function __construct($numerator = null, $denominator = null)
	{
		if ($numerator) {
			$this->setNumerator($numerator);
		}
		if ($denominator) {
			$this->setDenominator($denominator);
		}
	}


	public function __toString(): string
	{
		return $this->getNumerator() . '/' . $this->getDenominator();
	}


	/**
	 * @return Fraction|string|null
	 */
	public function getNumerator()
	{
		if ($this->numeratorFraction) {
			return $this->numeratorFraction;
		} else {
			return $this->numeratorString;
		}
	}


	/**
	 * @param int|string|Stringable|BigNumber|Fraction $numerator
	 */
	public function setNumerator($numerator): void
	{
		if ($numerator instanceof self) {
			$this->numeratorString = null;
			$numerator->setParentInNumerator($this);
			$this->numeratorFraction = $numerator;
		} else {
			$this->numeratorFraction = null;
			$this->numeratorString = (string) $numerator;
		}
	}


	/**
	 * @return Fraction|string|null
	 */
	public function getDenominator()
	{
		if ($this->denominatorFraction) {
			return $this->denominatorFraction;
		} else {
			return $this->denominatorString;
		}
	}


	/**
	 * @return Fraction|string
	 */
	public function getDenominatorNotNull()
	{
		return $this->getDenominator() ?: '1';
	}


	/**
	 * @param int|string|Stringable|BigNumber|Fraction $denominator
	 */
	public function setDenominator($denominator): void
	{
		if ($denominator instanceof self) {
			$this->denominatorString = null;
			$denominator->setParentInDenominator($this);
			$this->denominatorFraction = $denominator;
		} else {
			$this->denominatorFraction = null;
			$this->denominatorString = (string) $denominator;
		}
	}


	public function getParent(): ?self
	{
		if ($this->parentInNumerator) {
			return $this->parentInNumerator;
		} else {
			return $this->parentInDenominator;
		}
	}


	/**
	 * @param Fraction|null $parentInNumerator
	 * @return Fraction
	 */
	public function setParentInNumerator(?self $parentInNumerator): self
	{
		$this->parentInDenominator = null;
		$this->parentInNumerator = $parentInNumerator;
		return $this;
	}


	/**
	 * @param Fraction|null $parentInDenominator
	 * @return Fraction
	 */
	public function setParentInDenominator(?self $parentInDenominator): self
	{
		$this->parentInNumerator = null;
		$this->parentInDenominator = $parentInDenominator;
		return $this;
	}
}
