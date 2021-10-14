## FIQL Parser

[![Latest Stable Version](http://poser.pugx.org/prettus/php-fiql-parser/v)](https://packagist.org/packages/prettus/php-fiql-parser)
[![Total Downloads](http://poser.pugx.org/prettus/php-fiql-parser/downloads)](https://packagist.org/packages/prettus/php-fiql-parser)
[![License](http://poser.pugx.org/prettus/php-fiql-parser/license)](https://packagist.org/packages/prettus/php-fiql-parser)
[![PHP Version Require](http://poser.pugx.org/prettus/php-fiql-parser/require/php)](https://packagist.org/packages/prettus/php-fiql-parser)
[![Maintainability](https://api.codeclimate.com/v1/badges/e4204205a1e289b03f18/maintainability)](https://codeclimate.com/github/andersao/php-fiql-parser/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/e4204205a1e289b03f18/test_coverage)](https://codeclimate.com/github/andersao/php-fiql-parser/test_coverage)

A PHP parser for the Feed Item Query
Language ([FIQL](https://datatracker.ietf.org/doc/html/draft-nottingham-atompub-fiql-00)).

## Installation

```bash
composer require prettus/php-fiql-parser
```

## Using Parser

```php
use \Prettus\FIQLParser\Parser;
use \Prettus\FIQLParser\Expression;
use \Prettus\FIQLParser\Exceptions\FiqlException;

$expression = Parser::fromString('last_name==foo*,(age=lt=55;age=gt=5)');

print_r($expression->toArray());
print_r($expression->toJson());

/**
 * Output of toJson()
 *
 * {"or":[["last_name","==","foo*"],{"and":[["age","<","55"],["age",">","5"]]}]}
 */

/**
 * Output of toArray()
 *
 * [
 *     'or' => [
 *         ['last_name', '==', 'foo*'],
 *         [
 *             'and' => [
 *                 ['age', '<', 55],
 *                 ['age', '>', 5],
 *             ]
 *         ]
 *     ]
 * ]
 * /
```

## Using Builder

```php
use \Prettus\FIQLParser\Expression;
use \Prettus\FIQLParser\Constraint;
use \Prettus\FIQLParser\Operator;
use \Prettus\FIQLParser\Exceptions\FiqlException;

$expression = new Expression();
$expression->addElement(new Constraint('last_name', '==', 'foo*'));
$expression->addElement(new Operator(','));

$subExpression = new Expression();
$subExpression->addElement(new Constraint('age', '=lt=', '55'));
$subExpression->addElement(new Operator(';'));
$subExpression->addElement(new Constraint('age', '=gt=', '5'));

$expression->addElement($subExpression);

print_r(strval($expression));
// last_name==foo*,age=lt=55;age=gt=5
```

## Credits

This project is completely inspired by python [fiql-parser](https://github.com/sergedomk/fiql_parser)
