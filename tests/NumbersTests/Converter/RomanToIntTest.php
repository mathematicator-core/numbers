<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests\Converter;


use Mathematicator\Numbers\Converter\RomanToInt;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../Bootstrap.php';

class RomanToIntTest extends TestCase
{
	public function test1(): void
	{
		Assert::same('61', (string) RomanToInt::convert('LXI'));
		Assert::same('2601', (string) RomanToInt::convert('MMDCI'));
		Assert::same('28', (string) RomanToInt::convert('XXVIII'));
		Assert::same('XXXII', (string) RomanToInt::reverse('32'));
	}
}

(new RomanToIntTest())->run();
