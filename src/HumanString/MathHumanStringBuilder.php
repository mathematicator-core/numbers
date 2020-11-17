<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\HumanString;


use Mathematicator\Numbers\IMathBuilder;
use Stringable;

/**
 * @implements IMathBuilder<MathHumanStringBuilder>
 */
final class MathHumanStringBuilder implements IMathBuilder, Stringable
{
	private string $humanString;


	/**
	 * @param int|string|Stringable $humanString
	 */
	public function __construct($humanString = '')
	{
		$this->humanString = (string) $humanString;
	}


	public function getHumanString(): string
	{
		return $this->humanString;
	}


	public function __toString(): string
	{
		return $this->getHumanString();
	}


	/**
	 * @param int|string|Stringable $with
	 */
	public function plus($with): self
	{
		return $this->operator(MathHumanStringToolkit::PLUS, $with);
	}


	/**
	 * @param int|string|Stringable $with
	 */
	public function minus($with): self
	{
		return $this->operator(MathHumanStringToolkit::MINUS, $with);
	}


	/**
	 * @param int|string|Stringable $with
	 */
	public function multipliedBy($with): self
	{
		return $this->operator(MathHumanStringToolkit::MULTIPLY, $with);
	}


	/**
	 * @param int|string|Stringable $with
	 */
	public function dividedBy($with): self
	{
		return $this->operator(MathHumanStringToolkit::DIVIDE, $with);
	}


	/**
	 * @param int|string|Stringable $to
	 */
	public function equals($to): self
	{
		return $this->operator(MathHumanStringToolkit::EQUALS, $to);
	}


	/**
	 * @param int|string|Stringable $to
	 */
	public function operator(string $operator, $to): self
	{
		$this->humanString = (string) MathHumanStringToolkit::operator($this->humanString, $to, $operator);

		return $this;
	}


	public function wrap(string $left, string $right = null): self
	{
		$this->humanString = (string) MathHumanStringToolkit::wrap($this->humanString, $left, $right);

		return $this;
	}
}
