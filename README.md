<h1 align="center">
    Smart PHP Number Utilities
</h1>

<p align="center">
    <a href="https://mathematicator.com" target="_blank">
        <img src="https://avatars3.githubusercontent.com/u/44620375?s=100&v=4">
    </a>
</p>

[![Integrity check](https://github.com/mathematicator-core/numbers/workflows/Integrity%20check/badge.svg)](https://github.com/mathematicator-core/numbers/actions?query=workflow%3A%22Integrity+check%22)
[![codecov](https://codecov.io/gh/mathematicator-core/numbers/branch/master/graph/badge.svg)](https://codecov.io/gh/mathematicator-core/numbers)
[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg)](./LICENSE)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled%20L8-brightgreen.svg?style=flat)](https://phpstan.org/)


**A PHP library to safely store and represent numbers and its equivalents in PHP.**

Store lots of number types exactly (**integers, decimals, fractions**) and convert them to each other.
Expressions can be outputted as a **human string** (e.g. `1 / 2`) or **LaTeX** (e.g. `\frac{1}{2}`).

## Installation

```bash
composer require mathematicator-core/numbers
```

## Features

- **SmartNumber** - Unified safe storage for all number types with
    an arbitrary precision.
- **Fractions:**
    - **Entity\Fraction** - Storage for simple or compound fraction that
    can consist from numbers and other expressions.
    - **Entity\FractionNumbersOnly** - Storage for simple or compound fraction
    that consists only from numbers and is directly computable.
- **LaTeX support:** ([What is LaTeX?](https://en.wikipedia.org/wiki/LaTeX))
    - **MathLatexBuilder** - Create valid LaTeX for mathematical expressions
    in simple way with a fluent interface.
    - **MathLatexToolkit** - Statical library for LaTeX expressions
    (includes constants, functions, operators etc.)
    - **MathLatexSnippet** - Storage for LaTeX syntax.
- **Human string support:**
    - **MathHumanStringBuilder** - same interface as MathLatexBuilder,
    but produces human strings
    - **MathHumanStringToolkit** - same interface as MathLatexToolkit,
    but produces human strings (e.g. `1 / 2 * (3 + 1)`)
- **Set generators**
    - Primary numbers
    - Even numbers
    - Odd numbers
- **Converters:**
    - Array to fraction
    - Decimal to fraction
    - Fraction to array
    - Fraction to human string
    - Fraction to LaTeX

💡 **TIP:** You can use [mathematicator-core/tokenizer](https://github.com/mathematicator-core/tokenizer)
for advance user input string **tokenization**.

## Usage

```php
use Mathematicator\Numbers\SmartNumber;

$smartNumber = new SmartNumber(10, '80.500'); // accuracy, number
echo $smartNumber->getFloat(); // 80.5
echo $smartNumber->getFraction()->getNumerator(); // 161
echo $smartNumber->getFraction()->getDenominator(); // 2
echo $smartNumber->getHumanString(); // 161/2
echo $smartNumber->getLatex(); // \frac{161}{2}
```

## Recommended libraries

For safe operations with arbitrary length numbers we recommend to use:

- [brick/math](https://github.com/brick/math) - Arbitrary precision
arithmetic library for PHP with a simple interface.
- [PHP BC Math extension](https://www.php.net/manual/en/ref.bc.php) - Native PHP extension for
arbitrary precision computing.

### Working with money

Use on of these libraries if you work with money in you application.

- [brick/money](https://github.com/brick/money) - A money and currency library
with interface like brick/math.
- [moneyphp/money](https://github.com/moneyphp/money) - Widely adopted PHP
implementation of Fowler's Money pattern.

## Why float is not safe?

**Float stores you number as an approximation with a limited precision.**

You should never trust float to the last digit. Do not use floats
directly for checking an equity if you rely on precision
(not only monetary calculations).

**Example:**
```php
$result = 0.1 + 0.2;
echo $result; // output: 0.3

echo ($result == 0.3) ? 'true' : 'false'; // output: false
```

[How is float stored in memory?](https://softwareengineering.stackexchange.com/a/215126/354697)

[See in PHP manual](https://www.php.net/manual/en/language.types.float.php)

[Read more about float on Wikipedia](https://en.wikipedia.org/wiki/Floating-point_arithmetic)

## Contribution

> Please help improve this documentation by sending a Pull request.

### Tests

All new contributions should have its unit tests in `/tests` directory.

Before you send a PR, please, check all tests pass.

This package uses [Nette Tester](https://tester.nette.org/). You can run tests via command:
```bash
composer test
````

Before PR, please run complete code check via command:
```bash
composer cs:install # only first time
composer fix # otherwise pre-commit hook can fail
````
