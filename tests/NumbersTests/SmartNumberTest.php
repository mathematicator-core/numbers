<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests;


use Brick\Math\RoundingMode;
use InvalidArgumentException;
use Mathematicator\Numbers\Helper\NumberHelper;
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


	public function testDecimal2(): void
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
		Assert::same('80.500', (string) $smartNumber->getHumanString());
		Assert::same('161/2', (string) $smartNumber->getFraction());
		Assert::same('80.500', (string) $smartNumber->getString());
		Assert::same('80.500', (string) $smartNumber->getLatex());

		// Operations
		Assert::same('322.000', (string) $smartNumber->getDecimal()->multipliedBy(4));
		Assert::same('-322.000', (string) $smartNumber->getDecimal()->multipliedBy(-4));
		Assert::same(322, $smartNumber->getDecimal()->multipliedBy(4)->abs()->toInt());
	}


	public function testPreFormatting(): void
	{
		$smartNumber = new SmartNumber(10, '10 000 80.500');
		Assert::same('1000080.5', (string) $smartNumber->getDecimal());
	}


	public function testPreFormattingWithCustomSeparators(): void
	{
		$smartNumber = new SmartNumber(10, NumberHelper::preprocessInput('10x000a80g500', ['g', '.'], ['', 'a', 'x', 'd']));
		Assert::same('1000080.5', (string) $smartNumber->getDecimal());
		Assert::same('1000081', (string) $smartNumber->getDecimal()->toScale(0, RoundingMode::HALF_UP));
	}


	/**
	 * @throws InvalidArgumentException
	 */
	public function testPreFormattingWithCustomSeparators2(): void
	{
		$smartNumber = new SmartNumber(10, NumberHelper::preprocessInput('10x000a80g500', ['g', 1], ['', 'a', 'x', 'd']));
	}


	public function testFractionPropertyClonability(): void
	{
		$smartNumber = new SmartNumber(10, '10 000 80.500');
		$fraction = $smartNumber->getFraction();
		Assert::same('2000161/2', (string) $smartNumber->getFraction());

		$newFraction = $fraction->setNumerator(1);
		Assert::same('2000161/2', (string) $smartNumber->getFraction());
		Assert::same('1/2', (string) $newFraction);
		Assert::same('1', (string) $newFraction->getNumerator());
	}


	public function testReadmeExample(): void
	{
		$smartNumber = new SmartNumber(10, '80.500');
		Assert::same('80.500', (string) $smartNumber->getDecimal());
		Assert::same('161', (string) $smartNumber->getFraction()->getNumerator());
		Assert::same('2', (string) $smartNumber->getFraction()->getDenominator());
		Assert::same('-322.000', (string) $smartNumber->getDecimal()->multipliedBy(-4));
		Assert::same('322', (string) $smartNumber->getDecimal()->multipliedBy(-4)->abs()->toInt());
		Assert::same('81', (string) $smartNumber->getDecimal()->toScale(0, RoundingMode::HALF_UP));

		$smartNumber2 = new SmartNumber(10, '161/2');
		Assert::same('161/2', (string) $smartNumber2->getHumanString());
		Assert::same('161/2+5=90.5', (string) $smartNumber2->getHumanString()->plus(5)->equals('90.5'));
		Assert::same('\frac{161}{2}', (string) $smartNumber2->getLatex());
		Assert::same('80.5', (string) $smartNumber2->getDecimal());
	}


	public function testBenchmarkCases(): void
	{
		$smartNumber = new SmartNumber(10, '158985102');
		Assert::same('158985102', (string) $smartNumber->getFraction()[0]);

		$smartNumber = new SmartNumber(10, '1482002/10');
		Assert::same('1482002', (string) $smartNumber->getFraction(false)->getNumerator());

		$smartNumber = new SmartNumber(10, '1482002/10');
		Assert::same('741001', (string) $smartNumber->getRational(true)->getNumerator());
	}
}

(new SmartNumberTest())->run();
