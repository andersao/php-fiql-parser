<?php

use \Prettus\FIQL\Parser;
use \Prettus\FIQL\Expression;

test('should parse fiql', function($str, $arr) {
    $expression = Parser::FIQL($str);
    expect($expression)->toBeInstanceOf(Expression::class);
    expect(strval($expression))->toEqual($str);
    expect($expression->toArray())->toEqual($arr);
})->with([
    ['last_name==foo*', ['last_name', '==', 'foo*']],
    ['description==foo bar*', ['description', '==', 'foo bar*']],
    ['last_name==foo*;age==30', [
        'and' => [
            ['last_name', '==', 'foo*'],
            ['age', '==', '30'],
        ]
    ]],
])->group('parser');