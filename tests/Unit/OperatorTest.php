<?php

use \Prettus\FIQL\Operator;
use \Prettus\FIQL\Exceptions\RequiredParam;

test('should throws exception if no pass operation in constructor', function() {
    $op = new Operator();
})->throws(RequiredParam::class);

test('should init a operator', function() {
    $op = new Operator(',');
    expect($op)->toBeObject();
});

test('should operator precedence', function() {
    $opAnd = new Operator(';');
    $opOr = new Operator(',');
    expect($opAnd)->toEqual(new Operator(';'));
    expect($opOr)->toEqual(new Operator(','));

    expect($opAnd)->not->toEqual($opOr);
    expect($opOr)->not->toEqual($opAnd);

    expect($opAnd->isGreaterThan($opOr))->toBeTrue();
    expect($opOr->isLessThan($opAnd))->toBeTrue();    
});