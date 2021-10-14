<?php

use \Prettus\FIQLParser\Operator;
use \Prettus\FIQLParser\Exceptions\FIQLObjectException;


test('should throws exception if pass invalid operator', function($op) {
    new Operator($op);
})->with([null,' ','.',])->throws(FIQLObjectException::class);

test('should init a operator or', function() {
    $op = new Operator(',');
    expect($op)->toBeObject();
    expect($op->toArray())->toEqual('or');
    expect(strval($op))->toEqual(',');
});

test('should init a operator and', function() {
    $op = new Operator(';');
    expect($op)->toBeObject();
    expect($op->toArray())->toEqual('and');
    expect(strval($op))->toEqual(';');
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