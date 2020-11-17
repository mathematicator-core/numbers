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


	/**
	 * @param int|string|Stringable $latex
	 */
	public function __construct($latex = '', ?string $delimiterLeft = null, ?string $delimiterRight = null)
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


	/**
	 * @param int|string|Stringable $with
	 */
	public function plus($with): self
	{
		return $this->operator(MathLatexToolkit::PLUS, $with);
	}


	/**
	 * @param int|string|Stringable $with
	 */
	public function minus($with): self
	{
		return $this->operator(MathLatexToolkit::MINUS, $with);
	}


	/**
	 * @param int|string|Stringable $with
	 */
	public function multipliedBy($with): self
	{
		return $this->operator(MathLatexToolkit::MULTIPLY, $with);
	}


	/**
	 * @param int|string|Stringable $with
	 */
	public function dividedBy($with): self
	{
		return $this->operator(MathLatexToolkit::DIVIDE, $with);
	}


	/**
	 * @param int|string|Stringable $to
	 */
	public function equals($to): self
	{
		return $this->operator(MathLatexToolkit::EQUALS, $to);
	}


	/**
	 * @param int|string|Stringable $to
	 */
	public function operator(string $operator, $to): self
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
