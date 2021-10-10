<?php
namespace Prettus\FIQL;

use \Prettus\FIQL\Contracts\Element;
use \Prettus\FIQL\Element as BaseElement;
use \Prettus\FIQL\Operator;

class Expression extends BaseElement {

    private $elements;
    public $workingFragment;
    private $lastElement;

    function __construct() {
        $this->elements = [];
        $this->operator = null;
        $this->workingFragment = $this;
        $this->lastElement = null;
    }

    public function hasConstraint() {
        return sizeof($this->elements) > 0;
    }

    public function createNestedExpression() {
        $sub = new Expression();
        $this->addElement($sub);
        return $sub;
    }

    public function opAnd(...$elements) {
        $expression = $this->addOperator(new Operator(';'));
        
        foreach($elements as $element) {
            $expression->addElement($element);
        }

        return $expression;
    }

    public function opOr(...$elements) {
        $expression = $this->addOperator(new Operator(','));
        
        foreach($elements as $element) {
            $expression->addElement($element);
        }

        return $expression;
    }

    public function addElement($element) {
        if($element instanceof Element) {
            $element->setParent($this);
            array_push($this->workingFragment->elements, $element);
            return $this;
        } else {
            return $this->addOperator($element);
        }
    }

    public function addOperator(Operator $operator) {
        $this->operator = $operator;

        if(!$this->workingFragment->operator) {
            $this->workingFragment->operator = $operator;
        } else if ($operator->isLessThan($this->workingFragment->operator)) {
            $lastConstraint = array_pop($this->workingFragment->elements);
            $this->workingFragment = $this->workingFragment->createNestedExpression();
            $this->workingFragment->addElement($lastConstraint);
            $this->workingFragment->addOperator($operator);
        } else if ($operator->isGreaterThan($this->workingFragment->operator)) {
            if ($this->workingFragment->parent) return $this->workingFragment->parent->addOperator($operator);
            return (new Expression()).addElement($this->workingFragment).addOperator($operator);
        }

        return $this;
    }

    public function __toString() {
        $operator = $this->operator;
        return join(strval($operator), array_map('strval', $this->elements));
    }
}