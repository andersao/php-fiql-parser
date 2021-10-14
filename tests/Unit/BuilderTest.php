<?php
use \Prettus\FIQLParser\Expression;
use \Prettus\FIQLParser\Constraint;
use \Prettus\FIQLParser\Operator;
use \Prettus\FIQLParser\Exceptions\FiqlException;

test('build a expression', function() {
    $expression = new Expression();
    $expression->addElement(new Constraint('last_name', '==', 'foo*'));
    $expression->addElement(new Operator(','));

    $subExpression = new Expression();
    $subExpression->addElement(new Constraint('age', '=lt=', '55'));
    $subExpression->addElement(new Operator(';'));
    $subExpression->addElement(new Constraint('age', '=gt=', '5'));

    $expression->addElement($subExpression);

    expect(strval($expression))->toEqual('last_name==foo*,age=lt=55;age=gt=5');
})->group('builder');
