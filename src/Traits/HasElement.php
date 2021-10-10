<?php
namespace Prettus\FIQL\Traits;

trait HasParent {
    private $parent;

    private function __construct() {
        parent::__construct();
        $this->$parent = null;
    }

    public function setParent($parent){
        return $this->parent = $parent;
    }
    
    public function getParent() {
        return $this->parent;
    }
}