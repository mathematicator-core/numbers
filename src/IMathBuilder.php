<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


/**
 * @template SelfClass
 */
interface IMathBuilder
{
	public function __toString(): string;

	public function plus(int|string|Stringable $with): self;

	public function minus(int|string|Stringable $with): self;

	public function multipliedBy(int|string|Stringable $with): self;

	public function dividedBy(int|string|Stringable $with): self;

	public function equals(int|string|Stringable $to): self;

	public function operator(string $operator, int|string|Stringable $to): self;

	public function wrap(string $left, string $right = null): self;
}
