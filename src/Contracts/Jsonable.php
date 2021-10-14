<?php

namespace Prettus\FIQLParser\Contracts;

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
interface Jsonable {
    /**
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);
}
