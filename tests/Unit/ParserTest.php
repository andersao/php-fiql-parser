<?php

use \Prettus\FIQL\Parser;
use \Prettus\FIQL\Expression;

test('should parse', function() {
    $expression = Parser::FIQL('last_name==foo*');
    expect($expression)->toBeInstanceOf(Expression::class);
    expect(strval($expression))->toEqual('last_name==foo*');
})->group('parser');