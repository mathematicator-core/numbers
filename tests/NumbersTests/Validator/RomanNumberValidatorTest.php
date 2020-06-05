<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests\Latex;


use Mathematicator\Numbers\Validator\RomanNumberValidator;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../Bootstrap.php';

class RomanNumberValidatorTest extends TestCase
{

	/**
	 * @dataProvider getValidInputs
	 * @param string $input
	 */
	public function testValidInputs(string $input): void
	{
		Assert::true(RomanNumberValidator::validate($input));
	}


	/**
	 * @dataProvider getInvalidInputs
	 * @param string $input
	 */
	public function testInvalidInputs(string $input): void
	{
		Assert::false(RomanNumberValidator::validate($input));
	}


	public function testZeroHandling(): void
	{
		Assert::true(RomanNumberValidator::validate('N', true));
		Assert::false(RomanNumberValidator::validate('N', false));
	}


	/**
	 * @return string[]
	 */
	public function getValidInputs(): array
	{
		return [['i'], ['I'], ['XII'], ['L'], ['_M'], ['N'], ['_MX']];
	}


	/**
	 * @return string[]
	 */
	public function getInvalidInputs(): array
	{
		return [[''], ['a'], ['-X'], ['-I'], ['aMMMCMXCIXI'], ['MMMM']];
	}
}

(new RomanNumberValidatorTest())->run();
