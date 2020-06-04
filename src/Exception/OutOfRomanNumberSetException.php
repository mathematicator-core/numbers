<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Exception;

/**
 * Exception thrown when result cannot be provided because final number would be out of allowed set.
 */
class OutOfRomanNumberSetException extends NumberException
{
	public function __construct(string $haystack = '')
	{
		parent::__construct('Roman numbers allows only integers >0 or fractions with denominator 12, "' . $haystack . '" given.');
	}
}
