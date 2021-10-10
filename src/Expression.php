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
        return $this;
    }

    public function __toString() {
        $operator = $this->operator;
        return join(strval($operator), array_map('strval', $this->elements));
    }
}