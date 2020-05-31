<?php

declare(strict_types=1);

namespace Mathematicator\Numbers\Entity;


use Brick\Math\BigNumber;
use Mathematicator\Numbers\Converter\FractionToArray;
use Mathematicator\Numbers\Exception\NumberException;
use RuntimeException;
use Stringable;

trait FractionArrayAccessTrait
{

	/**
	 * @param int $offset
	 * @param int|string|Stringable|BigNumber|Fraction|null $value
	 */
	public function offsetSet($offset, $value): void
	{
		if ($offset === 0) {
			$this->setNumerator($value);
		} elseif ($offset === 1) {
			$this->setDenominator($value);
		} else {
			throw new RuntimeException("Offset $offset could not exist for fractions.");
		}
	}


	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset): bool
	{
		return $offset === 0 || $offset === 1;
	}


	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void
	{
		if ($offset === 0) {
			$this->setNumerator(null);
		} elseif ($offset === 1) {
			$this->setDenominator(null);
		}
	}


	/**
	 * @param mixed $offset
	 * @return mixed[]|string|null Returns recursively [string, string] or string value
	 * @throws NumberException
	 */
	public function offsetGet($offset)
	{
		$arr = FractionToArray::convert($this);

		return $arr[$offset] ?? null;
	}
}
