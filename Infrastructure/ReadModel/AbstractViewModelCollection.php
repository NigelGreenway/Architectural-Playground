<?php

namespace Demo\Infrastructure\ReadModel;

use ArrayAccess;
use JsonSerializable;

class AbstractViewModelCollection implements ArrayAccess, JsonSerializable
{
    private $elements = [];

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    public function add(AbstractViewModel $element)
    {
        $this->elements[] = $element;
    }

    /**
     * Required by ArrayAccess Interface
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    /**
     * Required by ArrayAccess Interface
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->elements[$offset];
    }

    /**
     * Required by ArrayAccess Interface
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->elements[$offset] = $value;
    }

    /**
     * Require by ArrayAccess Interface
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }

    public function toArray()
    {
        return $this->elements;
    }

    public function jsonSerialize()
    {
        return json_encode([
            'items' => $this->toArray(),
        ]);
    }
}