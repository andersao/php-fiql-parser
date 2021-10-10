<?php

use \Prettus\FIQL\Constraint;

test('should init a Constraint', function() {
    $constraint = new Constraint('foo', '==', 'bar');
    expect($constraint->selector)->toEqual('foo');
    expect($constraint->comparison)->toEqual('==');
    expect($constraint->argument)->toEqual('bar');
    expect(strval($constraint))->toEqual('foo==bar');
});