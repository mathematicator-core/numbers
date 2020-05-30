<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests;


use Brick\Math\BigInteger;
use Mathematicator\Numbers\Latex\MathLatexBuilder;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../Bootstrap.php';

class MathLatexBuilderTest extends TestCase
{
	public function testPow(): void
	{
		$latex = MathLatexBuilder::pow(1, 2);
		Assert::same('{1}^{2}', (string) $latex);
	}


	public function testFrac(): void
	{
		$latex = MathLatexBuilder::frac(1, 2);
		Assert::same('\frac{1}{2}', (string) $latex);
	}


	public function testMultipliedBy(): void
	{
		$latex = MathLatexBuilder::frac(1, 2)->multipliedBy('10');
		Assert::same('\frac{1}{2}\ \cdot\ 10', (string) $latex);
	}


	public function testRecreateAndBrickMathNumber(): void
	{
		$latex = MathLatexBuilder::create(
			MathLatexBuilder::frac(1, 2)
		)->multipliedBy(BigInteger::of('10000000000000000000000'));
		Assert::same('\frac{1}{2}\ \cdot\ 10000000000000000000000', (string) $latex);
	}
}

(new MathLatexBuilderTest())->run();
