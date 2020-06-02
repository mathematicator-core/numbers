<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests\Converter;


use Mathematicator\Numbers\Converter\IntToRoman;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../Bootstrap.php';

class IntToRomanTest extends TestCase
{
	public function test1(): void
	{
		Assert::same('LXI', (string) IntToRoman::convert('61'));
		Assert::same('MMDCI', (string) IntToRoman::convert('2601'));
		Assert::same('XXVIII', (string) IntToRoman::convert('28'));
		Assert::same('21', (string) IntToRoman::reverse('XXI'));
	}
}

(new IntToRomanTest())->run();
