<?php
namespace Prettus\FIQL;

use \Prettus\FIQL\Exceptions\FIQLObjectException;

const OPERATOR_MAP = [
    ';' => ['and', 2],
    ',' => ['or', 1],
];

class Operator {
    private $value;

    function __construct(string $value = null) {
        if(!$value || !OPERATOR_MAP[$value]) throw new FIQLObjectException("$value is not a valid FIQL operator");
        $this->value = $value;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getPriority(): string {
        return OPERATOR_MAP[$this->getValue()][1];
    }

    public function getCondition(): string {
        return OPERATOR_MAP[$this->getValue()][0];
    }

    public function compare(Operator $other) {
        $precSelf = $this->getPriority();
        $precOther = $other->getPriority();

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

    public function toArray(){
        return $this->getCondition();
    }

    public function __toString(){
        return $this->value;
    }
}