<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Latex;


use Stringable;

final class MathLatexSnippet implements Stringable
{

	/** @var string */
	public $latex;

	/** @var string */
	private $delimiterLeft;

	/** @var string */
	private $delimiterRight;


	public function __construct(string $latex = '')
	{
		$this->latex = $latex;
	}


	public function __toString()
	{
		return $this->delimiterLeft . $this->latex . $this->delimiterRight;
	}


	/**
	 * @param string $left
	 * @param string|null $right
	 * @return MathLatexSnippet
	 */
	public function setDelimiters(string $left, string $right = null): self
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
		return [$this->delimiterLeft, $this->delimiterRight];
	}
}
