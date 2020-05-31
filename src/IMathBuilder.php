<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


use Stringable;

interface IMathBuilder
{
	public function __toString(): string;

	/**
	 * @param string|Stringable $with
	 * @return self
	 */
	public function plus($with): self;

	/**
	 * @param string|Stringable $with
	 * @return self
	 */
	public function minus($with): self;

	/**
	 * @param string|Stringable $with
	 * @return self
	 */
	public function multipliedBy($with): self;

	/**
	 * @param string|Stringable $with
	 * @return self
	 */
	public function dividedBy($with): self;

	/**
	 * @param string|Stringable $to
	 * @return self
	 */
	public function equals($to): self;

	/**
	 * @param string $operator
	 * @param string|Stringable $to
	 * @return self
	 */
	public function operator(string $operator, $to): self;

	public function wrap(string $left, string $right = null): self;
}
