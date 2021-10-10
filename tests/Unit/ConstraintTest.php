<?php

use \Prettus\FIQL\Constraint;
use \Prettus\FIQL\Expression;
use \Prettus\FIQL\Exceptions\FIQLObjectException;

test('should init a Constraint', function() {
    $constraint = new Constraint('foo', '==', 'bar');
    expect($constraint->selector)->toEqual('foo');
    expect($constraint->comparison)->toEqual('==');
    expect($constraint->argument)->toEqual('bar');
    expect(strval($constraint))->toEqual('foo==bar');
});

test('should init a Constraint with default values', function() {
    $constraint = new Constraint('foo');
    expect($constraint->selector)->toEqual('foo');
    expect($constraint->comparison)->toBeEmpty();
    expect($constraint->argument)->toBeEmpty();
    expect(strval($constraint))->toEqual('foo');
});

test('should throw an erro if invalid comparison', function($comparison) {
    new Constraint('foo', $comparison, 'bar');
})->with(['=gt'])->throws(FIQLObjectException::class);

test('constraint set parent', function() {
    $constraint = new Constraint('foo');
    $expression = new Expression();
    $constraint->setParent($expression);
    expect($constraint->getParent())->toEqual($expression);
});

test('constraint fluent', function() {
    $constraint = (new Constraint('foo', '==', 'bar'))->opOr(
        (new Constraint('age', '=lt=', '55'))->opAnd(
            new Constraint('age', '=gt=', '5')
        )
    );

    expect($constraint)->toBeInstanceOf(Expression::class);
    expect(strval($constraint))->toEqual("foo==bar,age=lt=55;age=gt=5");
})->group('constraint');