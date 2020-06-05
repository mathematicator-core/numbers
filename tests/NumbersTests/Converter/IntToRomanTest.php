<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests\Converter;


use Mathematicator\Numbers\Converter\IntToRoman;
use Mathematicator\Numbers\Exception\OutOfRomanNumberSetException;
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
		Assert::same('C', (string) IntToRoman::convert('1e2'));
		Assert::same('C', (string) IntToRoman::convert('1e2'));
		Assert::same('I', (string) IntToRoman::convert('12/12'));
		Assert::same('X', (string) IntToRoman::convert('120/12'));
		Assert::same('·', (string) IntToRoman::convert('1/12'));
		Assert::same('S', (string) IntToRoman::convert('6/12'));
		Assert::same('IS·', (string) IntToRoman::convert('19/12'));
		Assert::same('MMMCMXCIX', (string) IntToRoman::convert('3999'));
	}


	/**
	 * @dataProvider getOutOfSetInputs
	 * @param string $input
	 */
	public function testOutOfSetInputs(string $input): void
	{
		Assert::throws(function () use ($input) {
			IntToRoman::convert($input);
		}, OutOfRomanNumberSetException::class);
	}


	/**
	 * @dataProvider getOutOfIntegerSetInputs
	 * @param string $input
	 */
	public function testOutOfIntegerSetInputs(string $input): void
	{
		Assert::throws(function () use ($input) {
			IntToRoman::convertInteger($input);
		}, OutOfRomanNumberSetException::class);
	}


	/**
	 * @return string[]
	 */
	public function getOutOfSetInputs(): array
	{
		return [['0'], ['-1'], ['-256'], ['-9998123456'], ['-25.2'], ['1.3'], ['4000'], ['1000000'], ['54856178844']];
	}


	/**
	 * @return string[]
	 */
	public function getOutOfIntegerSetInputs(): array
	{
		return [['0'], ['-1'], ['-256'], ['-9998123456'], ['-25.2'], ['1/2'], ['1.3']];
	}
}

(new IntToRomanTest())->run();
