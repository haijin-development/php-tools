<?php

namespace Haijin\Errors;

class OutOfRangeError extends HaijinError
{
    public function __construct($message, $collection, $index)
    {
        parent::__construct($message);

        $this->collection = $collection;
        $this->index = $index;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getIndex()
    {
        return $this->index;
    }
}