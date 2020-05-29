<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Tests;


use Mathematicator\Numbers\MandelbrotSetRequest;
use Mathematicator\Numbers\SmartNumber;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

class SmartNumberTest extends TestCase
{


	public function testEntity(): void
	{
		$smartNumber = new SmartNumber(0,"10");
		Assert::same('10', $smartNumber->getInteger());
	}

}

Bootstrap::boot();
(new SmartNumberTest())->run();