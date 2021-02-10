<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Latex;


final class MathLatexSnippet implements Stringable
{

	/** @internal */
	public string $latex;

	private ?string $delimiterLeft = null;

	private ?string $delimiterRight = null;


	public function __construct(string $latex = '')
	{
		$this->latex = $latex;
	}


	public function __toString(): string
	{
		return $this->delimiterLeft . $this->latex . $this->delimiterRight;
	}


	public function getLatex(): string
	{
		return $this->latex;
	}


	public function setDelimiters(string $left, ?string $right = null): self
	{
		$this->delimiterLeft = $left;
		$this->delimiterRight = $right ?: $left;

		return $this;
	}


	/**
	 * @return string[]
	 */
	public function getDelimiters(): array
	{
		return [(string) $this->delimiterLeft, (string) $this->delimiterRight];
	}
}
