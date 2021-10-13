<?php

use \Prettus\FIQL\Constants;

$regexMatch = function ($regx, $str, $mode = PREG_PATTERN_ORDER) {
    preg_match_all($regx, $str, $matches, $mode);
    return $matches;
};

$constraintRegex = function ($str) use($regexMatch) {
    return $regexMatch(Constants::CONSTRAINT_REGEX, $str);
};

$pctEncodingRegex = function ($str) use($regexMatch) {
    return $regexMatch('/'.Constants::PCT_ENCODING_REGEX.'$/', $str, PREG_SET_ORDER);
};

$unreservedRegex = function ($str) use($regexMatch) {
    return $regexMatch('/'.Constants::UNRESERVED_REGEX.'+$/', $str, PREG_SET_ORDER);
};

$delimitRegex = function ($str) use($regexMatch) {
    return $regexMatch('/'.Constants::FIQL_DELIM_REGEX.'+$/', $str, PREG_SET_ORDER);
};

$selectorRegex = function ($str) use($regexMatch) {
    return $regexMatch('/'.Constants::SELECTOR_REGEX.'$/', $str, PREG_SET_ORDER);
};

$comparisonRegex = function ($str) use($regexMatch) {
    return $regexMatch('/'.Constants::COMPARISON_REGEX.'$/', $str, PREG_SET_ORDER);
};

$argumentRegex = function ($str) use($regexMatch) {
    return $regexMatch('/'.Constants::ARGUMENT_REGEX.'$/', $str, PREG_SET_ORDER);
};

test('pct encoding not empty', function ($value) use($pctEncodingRegex) {
    expect($pctEncodingRegex($value))->not->toBeEmpty();
})->with(['%5E', '%AF', '%02', '%C4', '%ad', '%2b', '%f1'])->group('regex');

test('pct encoding is empty', function ($value) use($pctEncodingRegex) {
    expect($pctEncodingRegex($value))->toBeEmpty();
})->with(['###', '%A', '%G1', '%AAA'])->group('regex');

test('unreserved not empty', function ($value) use($unreservedRegex) {
    expect($unreservedRegex($value))->not->toBeEmpty();
})->with(['POIUYTREWQASDFGHJKLMNBVCXZ', 'qwertyuioplkjhgfdsazxcvbnm', '1234567890._-~'])->group('regex');

test('unreserved is empty', function ($value) use($unreservedRegex) {
    expect($unreservedRegex($value))->toBeEmpty();
})->with([':','/','?','#','[',']','@','!','$','&','\'','(',')','*',',',';','='])->group('regex');

test('fiql delimt is empty', function () use($delimitRegex) {
    expect($delimitRegex("="))->toBeEmpty();
})->group('regex');

test('fiql delimt is not empty', function () use($delimitRegex) {
    expect($delimitRegex("!$'*+"))->not->toBeEmpty();
})->group('regex');

test('argument is empty', function ($value) use($argumentRegex) {
    expect($argumentRegex($value))->toBeEmpty();
})->with(['?', '&', ',', ';', ''])->group('regex');

test('argument is not empty', function ($value) use($argumentRegex) {
    expect($argumentRegex($value))->not->toBeEmpty();
})->with(["ABC%3Edef_34~.-%04!$'*+:="])->group('regex');

test('selector is empty', function ($value) use($selectorRegex) {
    expect($selectorRegex($value))->toBeEmpty();
})->with(['#', '!', '=', ''])->group('regex');

test('selector is not empty', function ($value) use($selectorRegex) {
    expect($selectorRegex($value))->not->toBeEmpty();
})->with(["ABC%3Edef_34%04"])->group('regex');

test('comparison is empty', function ($value) use($comparisonRegex) {
    expect($comparisonRegex($value))->toBeEmpty();
})->with(['=', '=gt', '=01='])->group('regex');

test('comparison is not empty', function ($value) use($comparisonRegex) {
    expect($comparisonRegex($value))->not->toBeEmpty();
})->with(["=gt=","=ge=","=lt=","=le=","!=","$=","'=","*=","+=","=="])->group('regex');