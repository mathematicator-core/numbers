<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests;


use Mathematicator\Numbers\SmartNumber;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../Bootstrap.php';

class SmartNumberTest extends TestCase
{
	public function testInt(): void
	{
		$smartNumber = new SmartNumber(0, '10');
		Assert::same('10', $smartNumber->getInteger());
	}


	public function testDecimal(): void
	{
		$smartNumber = new SmartNumber(10, '10.125');
		Assert::same('10', $smartNumber->getInteger());
		Assert::same(10.125, $smartNumber->getFloat());
		Assert::same('10.125', $smartNumber->getFloatString());
	}


	public function testFraction(): void
	{
		$smartNumber = new SmartNumber(10, '80.500');
		Assert::same(80.5, $smartNumber->getFloat());
		Assert::same('161', (string) $smartNumber->getFraction()->getNumerator());
		Assert::same('2', (string) $smartNumber->getFraction()->getDenominator());
		Assert::same('161/2', (string) $smartNumber->getHumanString());
		Assert::same('\frac{161}{2}', (string) $smartNumber->getString());
		Assert::same('\frac{161}{2}', (string) $smartNumber->getLatex(true));
		Assert::same('80.5', (string) $smartNumber->getLatex(false));
	}
}

(new SmartNumberTest())->run();
