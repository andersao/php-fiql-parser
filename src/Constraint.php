<?php

namespace Prettus\FIQL;

use \Prettus\FIQL\Exceptions\FIQLObjectException;
use \Prettus\FIQL\Element as BaseElement;

const COMPARISON_MAP = [
    '==' => '==',
    '!=' => '!=',
    '=gt=' => '>',
    '=ge=' => '>=',
    '=lt=' => '<',
    '=le=' => '<=',
];

function isValidComparison($comparison)
{
    preg_match_all(Constants::COMPARISON_COMP, $comparison, $matches, PREG_SET_ORDER);
    return sizeof($matches) > 0;
}

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
class Constraint extends BaseElement
{
    /**
     * @param string $selector
     * @param string $comparison
     * @param string $argument
     * @throws FIQLObjectException
     */
    function __construct(string $selector, $comparison = '', $argument = '')
    {
        parent::__construct();
        if ($comparison and !isValidComparison($comparison)) {
            throw new FIQLObjectException(sprintf("'%s' is not a valid FIQL comparison", $comparison));
        }

        $this->selector = $selector;
        $this->comparison = $comparison;
        $this->argument = $argument;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $value = array_key_exists($this->comparison, COMPARISON_MAP) ? COMPARISON_MAP[$this->comparison] : null;
        return [
            $this->selector,
            $value ? : $this->comparison,
            $this->argument
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s%s%s', $this->selector, $this->comparison, $this->argument);
    }

    /**
     * @param ...$elements
     * @return Expression
     */
    public function opOr(...$elements): Expression
    {
        $expression = new Expression();
        return $expression->opOr($this, ...$elements);
    }

    /**
     * @param ...$elements
     * @return Expression
     */
    public function opAnd(...$elements): Expression
    {
        $expression = new Expression();
        return $expression->opAnd($this, ...$elements);
    }
}
