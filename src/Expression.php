<?php

namespace Prettus\FIQL;

use \Prettus\FIQL\Contracts\Element;
use \Prettus\FIQL\Element as BaseElement;

class Expression extends BaseElement
{

    public $elements;
    public $workingFragment;
    public $lastElement;

    function __construct()
    {
        parent::__construct();
        $this->elements = [];
        $this->operator = null;
        $this->workingFragment = $this;
        $this->lastElement = null;
    }

    public function hasConstraint()
    {
        return sizeof($this->elements) > 0;
    }

    public function createNestedExpression()
    {
        $sub = new Expression();
        $this->addElement($sub);
        return $sub;
    }

    public function opAnd(...$elements)
    {
        $expression = $this->addOperator(new Operator(';'));

        foreach ($elements as $element) {
            $expression->addElement($element);
        }

        return $expression;
    }

    public function opOr(...$elements)
    {
        $expression = $this->addOperator(new Operator(','));

        foreach ($elements as $element) {
            $expression->addElement($element);
        }

        return $expression;
    }

    public function addElement($element)
    {
        if ($element instanceof Element) {
            $element->setParent($this);
            array_push($this->workingFragment->elements, $element);
            return $this;
        } else {
            return $this->addOperator($element);
        }
    }

    public function addOperator(Operator $operator)
    {
        if (!$this->workingFragment->operator) {
            $this->workingFragment->operator = $operator;
        } else if ($operator->isGreaterThan($this->workingFragment->operator)) {
            $lastConstraint = array_pop($this->workingFragment->elements);
            $this->workingFragment = $this->workingFragment->createNestedExpression();
            $this->workingFragment->addElement($lastConstraint);
            $this->workingFragment->addOperator($operator);
        } else if ($operator->isLessThan($this->workingFragment->operator)) {
            if ($this->workingFragment->hasParent()) return $this->workingFragment->getParent()->addOperator($operator);
            return (new Expression())->addElement($this->workingFragment)->addOperator($operator);
        }

        return $this;
    }

    public function toArray()
    {
        $countElements = sizeof($this->elements);

        if ($countElements == 0) return null;
        if ($countElements == 1) return $this->elements[0]->toArray();

        $operator = $this->operator ? $this->operator : new Operator(';');

        return [
            $operator->toArray() => array_map(function ($el) {
                return $el->toArray();
            }, $this->elements)
        ];
    }

    public function __toString()
    {
        $operator = $this->operator ?: new Operator(';');
        $elementsStr = join(strval($operator), array_map('strval', $this->elements));

        if ($this->hasParent()) {
            $parent = $this->getParent();
            $parentOperator = $parent->operator ?: new Operator(';');

            if ($parentOperator->isGreaterThan($operator)) {
                return sprintf("(%s)", $elementsStr);
            }
        }

        return $elementsStr;
    }
}
