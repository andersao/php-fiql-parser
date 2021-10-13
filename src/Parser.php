<?php
namespace Prettus\FIQL;

use \Prettus\FIQL\Constants;
use \Prettus\FIQL\Constraint;
use \Prettus\FIQL\Expression;

class Parser {
    private static function iterableParse($fiqlStr) {
        $str = $fiqlStr;
        $matched = preg_split(Constants::CONSTRAINT_COMP, $str, 8, PREG_SPLIT_DELIM_CAPTURE);

        while(strlen($str) > 0) {

            if(sizeof($matched) < 2) {
                yield [$matched, null, null, null];
                break;
            }

            yield [$matched[0], $matched[1], $matched[4], $matched[6] ? $matched[6] : null];

            $str = $matched[9];
        }
    }

    static function FIQL($value): Expression {
        $nestingLevel = 0;
        $lastElement = null;
        $expression = new Expression();

        foreach(self::iterableParse($value) as $matched) {
            list($preamble, $selector, $comparison, $argument) = $matched;

            $el = new Constraint($selector, $comparison, $argument);
            $expression->addElement($el);
        }

        return $expression;
    }
}