<?php
namespace Prettus\FIQL;

use \Prettus\FIQL\Exceptions\RequiredParam;

const OPERATOR_MAP = [
    ';' => ['AND', 2],
    ',' => ['OR', 1],
];

class Operator {
    private $operator;

    function __construct(string $operator = null) {
        if(!$operator) throw new RequiredParam('Operator');
        $this->operator = $operator;
    }

    public function compare(Operator $other) {
        $precSelf = OPERATOR_MAP[$this->operator][1];
        $precOther = OPERATOR_MAP[$other->operator][1];

        if ($precSelf < $precOther) return -1;
        if ($precSelf > $precOther) return 1;

        return 0;
    }

    public function isGreaterThan(Operator $operator) {
        return $this->compare($operator) > 0;
    }

    public function isLessThan(Operator $operator) {
        return $this->compare($operator) < 0;
    }
}