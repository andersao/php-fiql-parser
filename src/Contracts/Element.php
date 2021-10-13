<?php

namespace Prettus\FIQL\Contracts;

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
interface Element
{
    public function setParent(Element $element);

    public function getParent(): Element;

    public function hasParent(): bool;
}
