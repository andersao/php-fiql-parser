<?php

use \Prettus\FIQL\Expression;
use \Prettus\FIQL\Constraint;
use \Prettus\FIQL\Operator;

test('constructor of expression', function() {
    $expression = new Expression();
    expect($expression)->toBeObject();
})->group('expression');

test('expression has constraint', function() {
    $expression = new Expression();
    expect($expression->hasConstraint())->toBeFalse();

    $expression->addElement(new Constraint(','));

    expect($expression->hasConstraint())->toBeTruthy();
})->group('expression');

test('expression add element', function() {
    $expression = new Expression();
    
    $expression->addElement(new Constraint('foo'));
    $expression->addElement(new Constraint('bar'));
    $expression->addElement(new Operator(';'));
    
    expect(strval($expression))->toEqual('foo;bar');
})->group('expression');


test('expression fluent', function() {
    $expression = (new Expression())->opOr(
        new Constraint('foo', '==', 'bar'),
        (new Expression())->opAnd(
            new Constraint('age', '=lt=', '55'),
            new Constraint('age', '=gt=', '5')
        )
    );

    expect(strval($expression))->toEqual("foo==bar,age=lt=55;age=gt=5");
})->group('expression');

test('expression create nested expression', function() {
    $expression = new Expression();
    $subExpression = $expression->createNestedExpression();
    $subSubExpression = $subExpression->createNestedExpression();

    expect($expression)->toEqual($subExpression->getParent());
    expect($subExpression)->toEqual($subSubExpression->getParent());
})->group('expression');

test('expression test to string', function() {
    $subExpression = new Expression();
    $subExpression->addElement(new Constraint('foo'));
    $subExpression->addElement(new Operator(';'));
    $subExpression->addElement(new Constraint('bar', '=gt=', '45'));
    $expression = new Expression();
    $expression->addElement(new Constraint('a', '==', 'wee'));
    $expression->addElement(new Operator(','));
    $expression->addElement($subExpression);
    $expression->addElement(new Operator(';'));
    $expression->addElement(new Constraint('key'));

    expect(strval($expression))->toEqual('a==wee,foo;bar=gt=45;key');
})->group('expression');