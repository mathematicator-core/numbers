<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\HumanString;


use Mathematicator\Numbers\IMathBuilder;

/**
 * @implements IMathBuilder<MathHumanStringBuilder>
 */
final class MathHumanStringBuilder implements IMathBuilder, Stringable
{
	private string $humanString;


	public function __construct(int|string|Stringable $humanString = '')
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


	public function plus(int|string|Stringable $with): self
	{
		return $this->operator(MathHumanStringToolkit::PLUS, $with);
	}


	public function minus(int|string|Stringable $with): self
	{
		return $this->operator(MathHumanStringToolkit::MINUS, $with);
	}


	public function multipliedBy(int|string|Stringable $with): self
	{
		return $this->operator(MathHumanStringToolkit::MULTIPLY, $with);
	}


	public function dividedBy(int|string|Stringable $with): self
	{
		return $this->operator(MathHumanStringToolkit::DIVIDE, $with);
	}


	public function equals(int|string|Stringable $to): self
	{
		return $this->operator(MathHumanStringToolkit::EQUALS, $to);
	}


	public function operator(string $operator, int|string|Stringable $to): self
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
