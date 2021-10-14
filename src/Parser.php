<?php

namespace Prettus\FIQLParser;

use \Prettus\FIQLParser\Exceptions\FiqlFormatException;

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
class Parser
{
    /**
     * @param string $value
     * @return \Generator
     */
    private static function iterableParse(string $value): \Generator
    {
        $str = $value;

        while (strlen($str) > 0) {
            $matched = preg_split(Constants::CONSTRAINT_COMP, $str, -1, PREG_SPLIT_DELIM_CAPTURE);

            if (sizeof($matched) < 2) {
                yield Utils::array_flatten([$matched[0], null, null, null]);
                break;
            }

            yield Utils::array_flatten([$matched[0], $matched[1], $matched[4], $matched[6] ? $matched[6] : null]);

            $str = $matched[9];
        }
    }

    /**
     * @param $value
     * @return Expression
     * @throws Exceptions\FIQLObjectException
     * @throws FiqlFormatException
     */
    static function fromString($value): Expression
    {
        $nestingLevel = 0;
        $lastElement = null;
        $expression = new Expression();

        foreach (self::iterableParse($value) as $matched) {
            list($preamble, $selector, $comparison, $argument) = $matched;

            if ($preamble) {
                foreach (str_split($preamble) as $char) {
                    if ($char == '(') {
                        if ($lastElement instanceof Element) {
                            throw new FiqlFormatException(sprintf('%s can not be followed by %s', get_class($lastElement), Expression::class));
                        }

                        $expression = $expression->createNestedExpression();
                        $nestingLevel += 1;
                    } elseif ($char == ')') {
                        $expression = $expression->getParent();
                        $lastElement = $expression;
                        $nestingLevel -= 1;
                    } else {
                        if (!$expression->hasConstraint()) {
                            throw new FiqlFormatException(sprintf('%s proceeding initial %s', Operator::class, Constraint::class));
                        }

                        if ($lastElement instanceof Operator) {
                            throw new FiqlFormatException(sprintf('%s can not be followed by %s', Operator::class, Operator::class));
                        }

                        $lastElement = new Operator($char);
                        $expression = $expression->addOperator($lastElement);
                    }
                }
            }

            if ($selector) {
                if ($lastElement instanceof Element) {
                    throw new FiqlFormatException(sprintf('%s can not be followed by %s', get_class($lastElement), Constraint::class));
                }
                $lastElement = new Constraint($selector, $comparison, $argument);
                $expression->addElement($lastElement);
            }
        }

        if ($nestingLevel != 0) {
            throw new FiqlFormatException('At least one nested expression was not correctly closed');
        }

        if (!$expression->hasConstraint()) {
            throw new FiqlFormatException(sprintf("Parsed string '%s' contained no constraint", $value));
        }

        return $expression;
    }
}
