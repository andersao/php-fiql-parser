<?php
namespace Prettus\FIQL;

class Constraint {
    function __construct(string $selector, string $comparison, string $argument) {
        $this->selector = $selector;
        $this->comparison = $comparison;
        $this->argument = $argument;
    }

    public function __toString() {
        return sprintf('%s%s%s', $this->selector, $this->comparison, $this->argument);
    }
}
