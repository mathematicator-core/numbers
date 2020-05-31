<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


use Stringable;

/**
 * @template SelfClass
 */
interface IMathBuilder
{
	public function __toString(): string;

	/**
	 * @param string|Stringable $with
	 * @return SelfClass
	 */
	public function plus($with);

	/**
	 * @param string|Stringable $with
	 * @return SelfClass
	 */
	public function minus($with);

	/**
	 * @param string|Stringable $with
	 * @return SelfClass
	 */
	public function multipliedBy($with);

	/**
	 * @param string|Stringable $with
	 * @return SelfClass
	 */
	public function dividedBy($with);

	/**
	 * @param string|Stringable $to
	 * @return SelfClass
	 */
	public function equals($to);

	/**
	 * @param string $operator
	 * @param string|Stringable $to
	 * @return SelfClass
	 */
	public function operator(string $operator, $to);

	/**
	 * @param string $left
	 * @param string|null $right
	 * @return SelfClass
	 */
	public function wrap(string $left, string $right = null);
}
