<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Latex;


use Stringable;

class MathLatexBuilder implements Stringable
{

	/** @var string */
	private $latex;


	/**
	 * @param string|Stringable $latex
	 */
	public function __construct($latex = '')
	{
		$this->latex = (string) $latex;
	}


	/**
	 * @param string|Stringable $latex
	 * @return MathLatexBuilder
	 */
	public static function create($latex = ''): self
	{
		return new self($latex);
	}


	/**
	 * @param string|Stringable $numerator
	 * @param string|Stringable $denominator
	 * @return MathLatexBuilder
	 */
	public static function frac($numerator, $denominator): self
	{
		return new self('\frac{' . $numerator . '}{' . $denominator . '}');
	}


	/**
	 * @param string|Stringable $x
	 * @param string|Stringable $pow
	 * @return MathLatexBuilder
	 */
	public static function pow($x, $pow): self
	{
		return new self('{' . $x . '}^{' . $pow . '}');
	}


	public function __toString()
	{
		return $this->latex;
	}


	/**
	 * @param string|Stringable $add
	 * @return MathLatexBuilder
	 */
	public function plus($add): self
	{
		$this->latex .= '\ +\ ' . $add;

		return $this;
	}


	/**
	 * @param string|Stringable $with
	 * @return MathLatexBuilder
	 */
	public function multipliedBy($with): self
	{
		$this->latex .= '\ \cdot\ ' . $with;

		return $this;
	}


	/**
	 * @param string|Stringable $to
	 * @return MathLatexBuilder
	 */
	public function equals($to): self
	{
		$this->latex .= '\ =\ ' . $to;

		return $this;
	}
}
