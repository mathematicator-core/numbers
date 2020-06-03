<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests;


use Brick\Math\RoundingMode;
use InvalidArgumentException;
use Mathematicator\Numbers\Exception\DivisionByZeroException;
use Mathematicator\Numbers\Exception\NumberFormatException;
use Mathematicator\Numbers\Helper\NumberHelper;
use Mathematicator\Numbers\SmartNumber;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../Bootstrap.php';

class SmartNumberTest extends TestCase
{
	public function testInt(): void
	{
		$smartNumber = new SmartNumber('10');
		Assert::same('10', (string) $smartNumber->getInteger());
	}


	public function testDecimal(): void
	{
		$smartNumber = new SmartNumber('10.125');
		Assert::same('10', (string) $smartNumber->getInteger());
		Assert::same(10.125, $smartNumber->getFloat());
		Assert::same('10.125', (string) $smartNumber->getDecimal());
	}


	public function testDecimal2(): void
	{
		$smartNumber = new SmartNumber('80.500');

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
		$smartNumber = new SmartNumber('10 000 80.500');
		Assert::same('1000080.5', (string) $smartNumber->getDecimal());
	}


	public function testPreFormattingWithCustomSeparators(): void
	{
		$smartNumber = new SmartNumber(NumberHelper::preprocessInput('10x000a80g500', ['g', '.'], ['', 'a', 'x', 'd']));
		Assert::same('1000080.5', (string) $smartNumber->getDecimal());
		Assert::same('1000081', (string) $smartNumber->getDecimal()->toScale(0, RoundingMode::HALF_UP));
	}


	/**
	 * @throws InvalidArgumentException
	 */
	public function testPreFormattingWithCustomSeparators2(): void
	{
		$smartNumber = new SmartNumber(NumberHelper::preprocessInput('10x000a80g500', ['g', 1], ['', 'a', 'x', 'd']));
	}


	public function testFractionPropertyClonability(): void
	{
		$smartNumber = new SmartNumber('10 000 80.500');
		$fraction = $smartNumber->getFraction();
		Assert::same('2000161/2', (string) $smartNumber->getFraction());

		$newFraction = $fraction->setNumerator(1);
		Assert::same('2000161/2', (string) $smartNumber->getFraction());
		Assert::same('1/2', (string) $newFraction);
		Assert::same('1', (string) $newFraction->getNumerator());
	}


	public function testReadmeExample(): void
	{
		$smartNumber = new SmartNumber('80.500');
		Assert::same('80.500', (string) $smartNumber->getDecimal());
		Assert::same('161', (string) $smartNumber->getFraction()->getNumerator());
		Assert::same('2', (string) $smartNumber->getFraction()->getDenominator());
		Assert::same('-322.000', (string) $smartNumber->getDecimal()->multipliedBy(-4));
		Assert::same('322', (string) $smartNumber->getDecimal()->multipliedBy(-4)->abs()->toInt());
		Assert::same('81', (string) $smartNumber->getDecimal()->toScale(0, RoundingMode::HALF_UP));

		$smartNumber2 = new SmartNumber('161/2');
		Assert::same('161/2', (string) $smartNumber2->getHumanString());
		Assert::same('161/2+5=90.5', (string) $smartNumber2->getHumanString()->plus(5)->equals('90.5'));
		Assert::same('\frac{161}{2}', (string) $smartNumber2->getLatex());
		Assert::same('80.5', (string) $smartNumber2->getDecimal());
	}


	public function testBenchmarkCases(): void
	{
		$smartNumber = new SmartNumber('158985102');
		Assert::same('158985102', (string) $smartNumber->getFraction()[0]);

		$smartNumber = new SmartNumber('1482002/10');
		Assert::same('1482002', (string) $smartNumber->getFraction(false)->getNumerator());

		$smartNumber = new SmartNumber('1482002/10');
		Assert::same('741001', (string) $smartNumber->getRational(true)->getNumerator());
	}


	public function testNonStandardInputs()
	{
		$smartNumber = new SmartNumber('25....');
		Assert::same('25', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('4.');
		Assert::same('4', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('-100/25');
		Assert::same('-100/25', (string) $smartNumber->getRational());
		Assert::same('-4', (string) $smartNumber->getInteger());
		$smartNumber = new SmartNumber('+4.');
		Assert::same('4', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('+5');
		Assert::same('5', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('+10/2');
		Assert::same('5', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('++10/2');
		Assert::same('5', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('-+10/2');
		Assert::same('-5', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('--10/2');
		Assert::same('5', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('--(10/2)');
		Assert::same('5', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('3.12e+2');
		Assert::same('312', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('3.12e+2');
		Assert::same('312', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('312e-2');
		Assert::same('3.12', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('1.5E-10');
		Assert::same('0.00000000015', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('-1.5E-10');
		Assert::same('-0.00000000015', (string) $smartNumber->getDecimal());
		Assert::same('-1.5E-10', (string) $smartNumber->getInput());
		$smartNumber = new SmartNumber('3.0012e2');
		Assert::same('300.12', (string) $smartNumber->getDecimal());
		$smartNumber = new SmartNumber('10.2/6.4');
		Assert::same('102/64', (string) $smartNumber->getRational());
	}


	public function testInfDecNumberFromRational()
	{
		$smartNumber = new SmartNumber('1/3');
		Assert::same('0.333', (string) $smartNumber->getDecimal(3));
		Assert::same('0.334', (string) $smartNumber->getDecimal(3, RoundingMode::UP));
		Assert::same('0', (string) $smartNumber->getInteger());

		$smartNumber = new SmartNumber('4/3');
		Assert::same('1', (string) $smartNumber->getInteger());
	}


	public function testDivisionByZero()
	{
		Assert::throws(function () {
			return new SmartNumber('4/0');
		}, DivisionByZeroException::class);
	}


	public function testBlankInput()
	{
		Assert::throws(function () {
			return new SmartNumber('');
		}, NumberFormatException::class);
	}
}

(new SmartNumberTest())->run();
