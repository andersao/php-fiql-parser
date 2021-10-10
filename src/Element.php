<?php
namespace Prettus\FIQL;

use \Prettus\FIQL\Contracts\Element as ElementInterface;

class Element implements ElementInterface {
    private $parent;

    private function __construct() {
        $this->$parent = null;
    }

    public function setParent(ElementInterface $parent){
        return $this->parent = $parent;
    }
    
    public function getParent(): ElementInterface {
        return $this->parent;
    }
}