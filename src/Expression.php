<?php

namespace Prettus\FIQLParser;

use Prettus\FIQLParser\Contracts\Arrayable;
use Prettus\FIQLParser\Contracts\Element;
use Prettus\FIQLParser\Contracts\Jsonable;
use Prettus\FIQLParser\Element as BaseElement;

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
class Expression extends BaseElement implements \Stringable, Arrayable, Jsonable
{
    /**
     * @var array
     */
    public $elements;

    /**
     * @var Expression
     */
    public $workingFragment;

    /**
     * @var mixed
     */
    public $lastElement;

    /**
     * @var Operator
     */
    private $operator;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->elements = [];
        $this->operator = null;
        $this->workingFragment = $this;
        $this->lastElement = null;
    }

    /**
     * @return bool
     */
    public function hasConstraint(): bool
    {
        return sizeof($this->elements) > 0;
    }

    /**
     * @return Expression
     */
    public function createNestedExpression(): Expression
    {
        $sub = new Expression();
        $this->addElement($sub);
        return $sub;
    }

    /**
     * @param ...$elements
     * @return Expression
     */
    public function opAnd(...$elements): Expression
    {
        $expression = $this->addOperator(new Operator(';'));

        foreach ($elements as $element) {
            $expression->addElement($element);
        }

        return $expression;
    }

    /**
     * @param ...$elements
     * @return Expression
     */
    public function opOr(...$elements): Expression
    {
        $expression = $this->addOperator(new Operator(','));

        foreach ($elements as $element) {
            $expression->addElement($element);
        }

        return $expression;
    }

    /**
     * @param $element
     * @return Expression
     */
    public function addElement($element): Expression
    {
        if ($element instanceof Element) {
            $element->setParent($this);
            array_push($this->workingFragment->elements, $element);
            return $this;
        } else {
            return $this->addOperator($element);
        }
    }

    /**
     * @param Operator $operator
     * @return $this|Expression
     */
    public function addOperator(Operator $operator): Expression
    {
        if (!$this->workingFragment->operator) {
            $this->workingFragment->operator = $operator;
        } elseif ($operator->isGreaterThan($this->workingFragment->operator)) {
            $lastConstraint = array_pop($this->workingFragment->elements);
            $this->workingFragment = $this->workingFragment->createNestedExpression();
            $this->workingFragment->addElement($lastConstraint);
            $this->workingFragment->addOperator($operator);
        } elseif ($operator->isLessThan($this->workingFragment->operator)) {
            if ($this->workingFragment->hasParent()) {
                return $this->workingFragment->getParent()->addOperator($operator);
            }
            return (new Expression())->addElement($this->workingFragment)->addOperator($operator);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $countElements = sizeof($this->elements);

        if ($countElements == 0) {
            return [];
        }
        if ($countElements == 1) {
            return $this->elements[0]->toArray();
        }

        $operator = $this->operator ?: new Operator(';');

        return [
            $operator->toArray() => array_map(function ($el) {
                return $el->toArray();
            }, $this->elements)
        ];
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return string
     */
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
