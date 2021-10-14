<?php

namespace Prettus\FIQLParser;

use Prettus\FIQLParser\Contracts\Arrayable;
use Prettus\FIQLParser\Contracts\Jsonable;
use \Prettus\FIQLParser\Exceptions\FIQLObjectException;

const OPERATOR_MAP = [
    ';' => ['and', 2],
    ',' => ['or', 1],
];

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
class Operator implements \Stringable, Arrayable, Jsonable
{
    private $value;

    /**
     * @param string|null $value
     * @throws FIQLObjectException
     */
    function __construct(string $value = null)
    {
        if (!$value || !array_key_exists($value, OPERATOR_MAP)) throw new FIQLObjectException("$value is not a valid FIQL operator");
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return OPERATOR_MAP[$this->getValue()][1];
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return OPERATOR_MAP[$this->getValue()][0];
    }

    /**
     * @param Operator $other
     * @return int
     */
    public function compare(Operator $other): int
    {
        $precSelf = $this->getPriority();
        $precOther = $other->getPriority();

        if ($precSelf < $precOther) return -1;
        if ($precSelf > $precOther) return 1;

        return 0;
    }

    /**
     * @param Operator $operator
     * @return bool
     */
    public function isGreaterThan(Operator $operator): bool
    {
        return $this->compare($operator) > 0;
    }

    /**
     * @param Operator $operator
     * @return bool
     */
    public function isLessThan(Operator $operator): bool
    {
        return $this->compare($operator) < 0;
    }

    /**
     * @return string
     */
    public function toArray(): string
    {
        return $this->getCondition();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
