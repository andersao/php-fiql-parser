<?php

namespace Prettus\FIQLParser;

use Prettus\FIQLParser\Contracts\Arrayable;
use Prettus\FIQLParser\Contracts\Jsonable;
use Prettus\FIQLParser\Exceptions\FIQLObjectException;
use Prettus\FIQLParser\Element as BaseElement;

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
class Constraint extends BaseElement implements \Stringable, Arrayable, Jsonable
{
    /**
     * @param string $selector
     * @param string $comparison
     * @param string $argument
     * @throws FIQLObjectException
     */
    public function __construct(string $selector, $comparison = '', $argument = '')
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
            $value ?: $this->comparison,
            $this->argument
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
