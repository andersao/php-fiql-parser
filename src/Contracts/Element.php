<?php
namespace Prettus\FIQL\Contracts;

interface Element {
    public function setParent(Element $element);
    public function getParent(): Element;
}