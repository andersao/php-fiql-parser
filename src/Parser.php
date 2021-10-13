<?php
namespace Prettus\FIQL;

use \Prettus\FIQL\Constants;
use \Prettus\FIQL\Constraint;
use \Prettus\FIQL\Expression;
use \Prettus\FIQL\Operator;
use \Prettus\FIQL\Utils;

class Parser {
    private static function iterableParse($fiqlStr) {
        $str = $fiqlStr;

        while(strlen($str) > 0) {
            $matched = preg_split(Constants::CONSTRAINT_COMP, $str, -1, PREG_SPLIT_DELIM_CAPTURE);

            if(sizeof($matched) < 2) {
                yield Utils::array_flatten([$matched[0], null, null, null]);
                break;
            }

            yield Utils::array_flatten([$matched[0], $matched[1], $matched[4], $matched[6] ? $matched[6] : null]);

            $str = $matched[9];
        }
    }

    static function FIQL($value): Expression {
        $nestingLevel = 0;
        $lastElement = null;
        $expression = new Expression();

        foreach(self::iterableParse($value) as $matched) {
            list($preamble, $selector, $comparison, $argument) = $matched;

            if($preamble) {
                foreach(str_split($preamble) as $char) {
                    if($char == '(') {
                        if($lastElement instanceof Element) throw new Error('query format error');
                        $expression = $expression->createNestedExpression();
                        $nestingLevel += 1;
                    }elseif($char == ')') {
                        $expression = $expression->getParent();
                        $lastElement = $expression;
                        $nestingLevel -= 1;
                    }else{
                        if(!$expression->hasConstraint()) throw new Error('proceeding initial');
                        if($lastElement instanceof Operator) throw new Error('can not be followed by');

                        $lastElement = new Operator($char);
                        $expression = $expression->addOperator($lastElement);
                    }
                }
            }

            if($selector) {
                if($lastElement instanceof Element) throw new Error('can not be followed by');
                $lastElement = new Constraint($selector, $comparison, $argument);
                $expression->addElement($lastElement);
            }
        }

        if($nestingLevel != 0) {
            throw new Error('At least one nested expression was not correctly closed');
        }

        if(!$expression->hasConstraint()) {
            throw new Error('contained no constraint');
        }

        return $expression;
    }
}