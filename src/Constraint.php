<?php
namespace Prettus\FIQL;

use \Prettus\FIQL\Exceptions\FIQLObjectException;
use \Prettus\FIQL\Contracts\Element;
use \Prettus\FIQL\Expression;
use \Prettus\FIQL\Element as BaseElement;

class Constraint extends BaseElement {
    function __construct(string $selector, string $comparison='', string $argument='') {
 
        if($comparison == '=gt') throw new FIQLObjectException(sprintf("'%s' is not a valid FIQL comparison", $comparison));

        $this->selector = $selector;
        $this->comparison = $comparison;
        $this->argument = $argument;
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
