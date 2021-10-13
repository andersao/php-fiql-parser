<?php
namespace Prettus\FIQL;

use \Prettus\FIQL\Exceptions\FIQLObjectException;
use \Prettus\FIQL\Contracts\Element;
use \Prettus\FIQL\Constants;
use \Prettus\FIQL\Expression;
use \Prettus\FIQL\Element as BaseElement;

const COMPARISON_MAP = [
    '==' => '==',
    '!=' => '!=',
    '=gt=' => '>',
    '=ge=' => '>=',
    '=lt=' => '<',
    '=le=' => '<=',
];

function isValidComparison($comparison) {
    preg_match_all(Constants::COMPARISON_COMP, $comparison, $matches, PREG_SET_ORDER);
    return sizeof($matches) > 0;
}

class Constraint extends BaseElement {
    function __construct(string $selector, string $comparison='', string $argument='') {

        if($comparison and !isValidComparison($comparison)) {
            throw new FIQLObjectException(sprintf("'%s' is not a valid FIQL comparison", $comparison));
        }

        $this->selector = $selector;
        $this->comparison = $comparison;
        $this->argument = $argument;
    }

    public function toArray() {
        $value = COMPARISON_MAP[$this->comparison];
        return [
            $this->selector,
            $value ? $value : $this->comparison,
            $this->argument
        ];
    }

    public function __toString() {
        return sprintf('%s%s%s', $this->selector, $this->comparison, $this->argument);
    }

    public function opOr(...$elements) {
        $expression = new Expression();
        return $expression->opOr($this, ...$elements);
    }

    public function opAnd(...$elements) {
        $expression = new Expression();
        return $expression->opAnd($this, ...$elements);
    }
}
