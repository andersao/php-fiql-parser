<?php

use \Prettus\FIQL\Expression;

test('should construct a Expression', function() {
    $expression = new Expression();
    expect($expression)->toBeObject();
});

test('should return an empty array after construct', function() {
    $expression = new Expression();
    $value = $expression->toArray();

    expect($value)->toBeArray();
    expect($value)->toBeEmpty();
});