<?php

use \Prettus\FIQL\Expression;
use \Prettus\FIQL\Constraint;
use \Prettus\FIQL\Operator;

test('constructor of expression', function() {
    $expression = new Expression();
    expect($expression)->toBeObject();
});

test('expression has constraint', function() {
    $expression = new Expression();
    expect($expression->hasConstraint())->toBeFalse();

    $expression->addElement(new Constraint(','));

    expect($expression->hasConstraint())->toBeTruthy();
});

test('expression add element', function() {
    $expression = new Expression();
    
    $expression->addElement(new Constraint('foo'));
    $expression->addElement(new Constraint('bar'));
    $expression->addElement(new Operator(';'));
    
    expect(strval($expression))->toEqual('foo;bar');
});