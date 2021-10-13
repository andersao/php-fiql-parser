<?php

use \Prettus\FIQL\Parser;
use \Prettus\FIQL\Expression;

test('should parse fiql', function($fiqlStr, $expectedStr, $expectedArray = []) {
    $expression = Parser::FIQL($fiqlStr);

    expect($expression)->toBeInstanceOf(Expression::class);
    expect(strval($expression))->toEqual($expectedStr);

    if(sizeof($expectedArray) > 0) {
        expect($expression->toArray())->toEqual($expectedArray);
    }
})->with([
    ['last_name==foo*', 'last_name==foo*', ['last_name', '==', 'foo*']],
    ['description==foo bar*', 'description==foo bar*', ['description', '==', 'foo bar*']],
    ['last_name==foo*;age==30', 'last_name==foo*;age==30', [
        'and' => [
            ['last_name', '==', 'foo*'],
            ['age', '==', '30'],
        ]
    ]],
    ['last_name==foo*,age==30;(gender==male)', 'last_name==foo*,age==30;gender==male'],
    ['last_name==foo*,(gender==male;year=gt=2000)', 'last_name==foo*,gender==male;year=gt=2000'],
    ['(first_name==bar*,last_name==foo*);(year=gt=1990;year=lt=2010)', 'first_name==bar*,last_name==foo*;year=gt=1990;year=lt=2010']
])->group('parser');