<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


use Stringable;

/**
 * @template Builder
 */
interface IMathToolkit
{
	public static function frac(string|Stringable $numerator, string|Stringable $denominator): IMathBuilder;

	public static function pow(string|Stringable $x, string|Stringable $pow): IMathBuilder;

	public static function sqrt(int|string|Stringable $expression, int|string|Stringable |null $n = null): IMathBuilder;

	public static function wrap(string|Stringable $content, string $left, ?string $right = null): IMathBuilder;

	/**
	 * Render function to valid LaTeX formula.
	 *
	 * @param array<int|string|Stringable|null> $arguments
	 */
	public static function func(
        string $name,
        array $arguments = [],
        int|string|Stringable |null $root = null
    ): IMathBuilder;

	public static function operator(string|Stringable $left, string|Stringable $right, string $operator): IMathBuilder;
}
