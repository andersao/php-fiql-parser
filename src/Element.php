<?php

namespace Prettus\FIQLParser;

use Prettus\FIQLParser\Contracts\Element as ElementInterface;

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
abstract class Element implements ElementInterface
{
    /**
     * @var ElementInterface
     */
    private $parent;

    public function __construct()
    {
        $this->parent = null;
    }

    /**
     * @param ElementInterface $parent
     * @return ElementInterface
     */
    public function setParent(ElementInterface $parent): ElementInterface
    {
        return $this->parent = $parent;
    }

    /**
     * @return ElementInterface
     */
    public function getParent(): ElementInterface
    {
        return $this->parent;
    }

    /**
     * @return bool
     */
    public function hasParent(): bool
    {
        return $this->parent != null;
    }
}
