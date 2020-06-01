<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests;


use Brick\Math\RoundingMode;
use InvalidArgumentException;
use Mathematicator\Numbers\SmartNumber;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../Bootstrap.php';

class SmartNumberTest extends TestCase
{
	public function testInt(): void
	{
		$smartNumber = new SmartNumber(0, '10');
		Assert::same('10', (string) $smartNumber->getInteger());
	}


	public function testDecimal(): void
	{
		$smartNumber = new SmartNumber(10, '10.125');
		Assert::same('10', (string) $smartNumber->getInteger());
		Assert::same(10.125, $smartNumber->getFloat());
		Assert::same('10.125', $smartNumber->getFloatString());
	}


	public function testFraction(): void
	{
		$smartNumber = new SmartNumber(10, '80.500');

		// Positivity
		Assert::same(true, $smartNumber->isPositive());
		Assert::same(false, $smartNumber->isNegative());
		Assert::same(false, $smartNumber->isZero());

		// Fractions
		Assert::same('161', (string) $smartNumber->getFraction()->getNumerator());
		Assert::same('2', (string) $smartNumber->getFraction()->getDenominator());

		// Outputs
		Assert::same(80.5, $smartNumber->getFloat());
		Assert::same('80', (string) $smartNumber->getInteger());
		Assert::same('161/2', (string) $smartNumber->getHumanString());
		Assert::same('\frac{161}{2}', (string) $smartNumber->getString());
		Assert::same('\frac{161}{2}', (string) $smartNumber->getLatex(true));
		Assert::same('80.5', (string) $smartNumber->getLatex(false));

		// Operations
		Assert::same('322.0', (string) $smartNumber->getDecimal()->multipliedBy(4));
		Assert::same('-322.0', (string) $smartNumber->getDecimal()->multipliedBy(-4));
		Assert::same(322, $smartNumber->getDecimal()->multipliedBy(4)->abs()->toInt());
	}


	public function testPreFormatting(): void
	{
		$smartNumber = new SmartNumber(10, '10 000 80,500');
		Assert::same('1000080.5', (string) $smartNumber->getDecimal());
	}


	public function testPreFormattingWithCustomSeparators(): void
	{
		$smartNumber = new SmartNumber(10, '10x000a80g500', ['g', '.'], ['', 'a', 'x', 'd']);
		Assert::same('1000080.5', (string) $smartNumber->getDecimal());
		Assert::same('1000081', (string) $smartNumber->getDecimal()->toScale(0, RoundingMode::HALF_UP));
	}


	/**
	 * @throws InvalidArgumentException
	 */
	public function testPreFormattingWithCustomSeparators2(): void
	{
		$smartNumber = new SmartNumber(10, '10x000a80g500', ['g', 1], ['', 'a', 'x', 'd']);
	}
}

(new SmartNumberTest())->run();
