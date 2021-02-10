<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Latex;


use Mathematicator\Numbers\IMathBuilder;
use Stringable;

/**
 * @implements IMathBuilder<MathLatexBuilder>
 */
final class MathLatexBuilder implements IMathBuilder, Stringable
{
	private MathLatexSnippet $snippet;


	public function __construct(
		int|string|Stringable $latex = '',
		?string $delimiterLeft = null,
		?string $delimiterRight = null
	)
	{
		$this->snippet = new MathLatexSnippet((string) $latex);

		if ($delimiterLeft) {
			$this->snippet->setDelimiters($delimiterLeft, $delimiterRight);
		}
	}


	public function getSnippet(): MathLatexSnippet
	{
		return $this->snippet;
	}


	public function __toString(): string
	{
		return (string) $this->snippet;
	}


	public function plus(int|string|Stringable $with): self
	{
		return $this->operator(MathLatexToolkit::PLUS, $with);
	}


	public function minus(int|string|Stringable $with): self
	{
		return $this->operator(MathLatexToolkit::MINUS, $with);
	}


	public function multipliedBy(int|string|Stringable $with): self
	{
		return $this->operator(MathLatexToolkit::MULTIPLY, $with);
	}


	public function dividedBy(int|string|Stringable $with): self
	{
		return $this->operator(MathLatexToolkit::DIVIDE, $with);
	}


	public function equals(int|string|Stringable $to): self
	{
		return $this->operator(MathLatexToolkit::EQUALS, $to);
	}


	public function operator(string $operator, int|string|Stringable $to): self
	{
		$this->snippet->latex = (string) MathLatexToolkit::operator($this->snippet->latex, $to, $operator);

		return $this;
	}


	public function wrap(string $left, string $right = null): self
	{
		$this->snippet->latex = (string) MathLatexToolkit::wrap($this->snippet->latex, $left, $right);

		return $this;
	}
}
