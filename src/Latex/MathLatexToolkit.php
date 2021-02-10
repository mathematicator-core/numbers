<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Latex;


use Mathematicator\Numbers\IMathToolkit;

/**
 * @implements IMathToolkit<MathLatexBuilder>
 */
final class MathLatexToolkit implements IMathToolkit
{
	public const PI = '\pi';

	public const INFINITY = '\infty';

	public const DEGREE = '\deg';

	public const PER_MILLE = '\permil';

	public const PLUS = '+';

	public const MINUS = '-';

	public const MULTIPLY = '\cdot';

	public const DIVIDE = '\div';

	public const EQUALS = '=';

	public const PERCENT = '\%';


	public static function create(
		int|string|Stringable $latex = '',
		?string $delimiterLeft = null,
		?string $delimiterRight = null
	): MathLatexBuilder {
		return new MathLatexBuilder($latex, $delimiterLeft, $delimiterRight);
	}


	public static function frac(int|string|Stringable $numerator, int|string|Stringable $denominator): MathLatexBuilder
	{
		return self::func('frac', [$numerator, $denominator]);
	}


	public static function pow(int|string|Stringable $x, int|string|Stringable $pow): MathLatexBuilder
	{
		return new MathLatexBuilder('{' . $x . '}^{' . $pow . '}');
	}


	public static function sqrt(
		int|string|Stringable $expression,
		int|string|Stringable |null $n = null
	): MathLatexBuilder {
		return self::func('sqrt', [$expression], $n);
	}


	public static function wrap(int|string|Stringable $content, string $left, string $right = null): MathLatexBuilder
	{
		return new MathLatexBuilder($left . $content . ($right ?: $left));
	}


	/**
	 * Render function to valid LaTeX formula.
	 *
	 * @param array<int|string|Stringable|null> $arguments
	 */
	public static function func(
		string $name,
		iterable $arguments = [],
		int|string|Stringable |null $root = null
	): MathLatexBuilder {
		$return = '\\' . $name;
		if ($root) {
			$return .= '[' . $root . ']';
		}
		foreach ($arguments as $argument) {
			$return .= '{' . $argument . '}';
		}

		return new MathLatexBuilder($return);
	}


	public static function operator(
		int|string|Stringable $left,
		int|string|Stringable $right,
		string $operator
	): MathLatexBuilder {
		return new MathLatexBuilder($left . '\ ' . $operator . '\ ' . $right);
	}
}
