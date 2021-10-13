<?php

use \Prettus\FIQL\Parser;
use \Prettus\FIQL\Expression;
use \Prettus\FIQL\Exceptions\FiqlException;


test('parse str to expression constraint only', function($fiqlStr, $expectedArray = []) {
    $expression = Parser::fromString($fiqlStr);

    expect($expression)->toBeInstanceOf(Expression::class);
    expect(strval($expression))->toEqual($fiqlStr);
    expect($expression->toArray())->toEqual($expectedArray);
})->with([
    ['foo=gt=bar', ['foo', '>', 'bar']],
    ['foo=le=bar', ['foo', '<=', 'bar']],
    ['foo!=bar', ['foo', '!=', 'bar']],
    ['foo==bar', ['foo', '==', 'bar']],
])->group('parser');

test('parse str to expression no args', function($fiqlStr, $expectedStr, $expectedArray = []) {
    $expression = Parser::fromString($fiqlStr);

    expect($expression)->toBeInstanceOf(Expression::class);
    expect(strval($expression))->toEqual($expectedStr);
    expect($expression->toArray())->toEqual($expectedArray);
})->with([
    ['foo', 'foo', ['foo', NULL, NULL]],
    ['((foo))', 'foo', ['foo', NULL, NULL]],
])->group('parser');

test('parse str to expression one operation', function($fiqlStr, $expectedArray = []) {
    $expression = Parser::fromString($fiqlStr);

    expect($expression)->toBeInstanceOf(Expression::class);
    expect(strval($expression))->toEqual($fiqlStr);
    expect($expression->toArray())->toEqual($expectedArray);
})->with([
    ['foo==bar;goo=gt=5', [
        'and' => [
            ['foo', '==', 'bar'],
            ['goo', '>', '5']
        ]
    ]],
    ['foo==bar;goo=gt=5;baa=lt=6', [
        'and' => [
            ['foo', '==', 'bar'],
            ['goo', '>', '5'],
            ['baa', '<', '6']
        ]
    ]],
    ['foo==bar,goo=lt=5', [
        'or' => [
            ['foo', '==', 'bar'],
            ['goo', '<', '5']
        ]
    ]],
    ['foo==bar,goo=lt=5,baa=gt=6', [
        'or' => [
            ['foo', '==', 'bar'],
            ['goo', '<', '5'],
            ['baa', '>', '6']
        ]
    ]]
])->group('parser');

test('parse str to expression explicit nesting', function($fiqlStr, $expectedStr, $expectedArray = []) {
    $expression = Parser::fromString($fiqlStr);
    expect($expression)->toBeInstanceOf(Expression::class);
    expect(strval($expression))->toEqual($expectedStr);
    expect($expression->toArray())->toEqual($expectedArray);
})->with([
    ['foo==bar,(goo=gt=5;goo=lt=10)', 'foo==bar,goo=gt=5;goo=lt=10', [
        'or' => [
            ['foo', '==', 'bar'],
            ['and' => [
                ['goo', '>', '5'],
                ['goo', '<', '10']
            ]]
        ]
    ]],
])->group('parser');

test('parse str to expression implicit nesting', function($fiqlStr, $expectedArray = []) {
    $expression = Parser::fromString($fiqlStr);
    expect($expression)->toBeInstanceOf(Expression::class);
    expect(strval($expression))->toEqual($fiqlStr);
    expect($expression->toArray())->toEqual($expectedArray);
})->with([
    ['foo==bar,goo=gt=5;goo=lt=10', [
        'or' => [
            ['foo', '==', 'bar'],
            ['and' => [
                ['goo', '>', '5'],
                ['goo', '<', '10']
            ]]
        ]
    ]],
])->group('parser');

test('parse str to expression failure', function($str) {
    $expression = Parser::fromString($str);
})->with([
    'foo=bar',
    'foo==',
    'foo=',
    ';;foo',
    '(foo)(bar)',
    '(foo==bar',
    'foo==bar(foo==bar)',
    ';foo==bar',
    'foo==bar;,foo==bar',
])->throws(FiqlException::class)->group('parser');
