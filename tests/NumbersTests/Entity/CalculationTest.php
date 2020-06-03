<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests\Entity;


use Brick\Math\RoundingMode;
use Mathematicator\Numbers\Calculation;
use Mathematicator\Numbers\Entity\Number;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../Bootstrap.php';

class CalculationTest extends TestCase
{
	public function testDecimal2(): void
	{
		$number = Number::of('80.500');

		// Operations
		Assert::same('322.000', (string) Calculation::of($number)->multipliedBy(4));
		Assert::same('-322.000', (string) Calculation::of($number)->multipliedBy(-4));
		Assert::same(322, Calculation::of($number)->multipliedBy(4)->abs()->getResult()->toBigInteger()->toInt());
	}


	public function testReadmeExample(): void
	{
		$number = Number::of('80.500');
		Assert::same('80.500', (string) $number->toBigDecimal());
		Assert::same('161', (string) $number->toFraction()->getNumerator());
		Assert::same('2', (string) $number->toFraction()->getDenominator());
		Assert::same('-322.000', (string) Calculation::of($number)->multipliedBy(-4));
		Assert::same('322', (string) Calculation::of($number)->multipliedBy(-4)->abs()->getResult()->toBigInteger()->toInt());
		Assert::same('81', (string) $number->toBigDecimal()->toScale(0, RoundingMode::HALF_UP));

		$number2 = Number::of('161/2');
		Assert::same('161/2', (string) $number2->toHumanString());
		Assert::same('161/2+5=90.5', (string) $number2->toHumanString()->plus(5)->equals('90.5'));
		Assert::same('\frac{161}{2}', (string) $number2->toLatex());
		Assert::same('80.5', (string) $number2->toBigDecimal());
	}
}

(new CalculationTest())->run();
