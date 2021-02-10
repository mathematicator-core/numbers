<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\HumanString;


use Mathematicator\Numbers\IMathToolkit;
use Stringable;

/**
 * @implements IMathToolkit<MathHumanStringBuilder>
 */
final class MathHumanStringToolkit implements IMathToolkit
{
	public const PI = 'π';

	public const DEGREE = '°';

	public const PER_MILLE = '‰';

	public const PLUS = '+';

	public const MINUS = '-';

	public const MULTIPLY = '*';

	public const DIVIDE = '/';

	public const EQUALS = '=';

	public const PERCENT = '%';


	public static function create(int|string|Stringable $humanString = ''): MathHumanStringBuilder
	{
		return new MathHumanStringBuilder($humanString);
	}


	public static function frac(int|string|Stringable $numerator, int|string|Stringable $denominator): MathHumanStringBuilder
	{
		return self::operator($numerator, $denominator, self::DIVIDE);
	}


	public static function pow(int|string|Stringable $x, int|string|Stringable $pow): MathHumanStringBuilder
	{
		return new MathHumanStringBuilder($x . '^(' . $pow . ')');
	}


	public static function sqrt(int|string|Stringable $expression, int|string|Stringable|null $n = null): MathHumanStringBuilder
	{
		return self::func('sqrt', [$expression], $n);
	}


	public static function wrap(int|string|Stringable $content, string $left, string $right = null): MathHumanStringBuilder
	{
		return new MathHumanStringBuilder($left . $content . ($right ?: $left));
	}


	/**
	 * Render function to valid human string formula.
	 *
	 * @param array<int|string|Stringable|null> $arguments
	 */
	public static function func(string $name, iterable $arguments = [], int|string|Stringable|null $root = null): MathHumanStringBuilder
	{
		$return = $name;
		if ($root) {
			$return .= '[' . $root . ']';
		}
		foreach ($arguments as $argument) {
			$return .= '(' . $argument . ')';
		}

		return new MathHumanStringBuilder($return);
	}


	public static function operator(int|string|Stringable $left, int|string|Stringable $right, string $operator): MathHumanStringBuilder
	{
		return new MathHumanStringBuilder($left . $operator . $right);
	}
}
