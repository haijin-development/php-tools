<?php

namespace Haijin\Tools;

class OutOfRangeError extends \Exception
{
    public function __construct($message, $collection, $index)
    {
        parent::__construct( $message );

        $this->collection = $collection;
        $this->index = $index;
    }

    public function get_collection()
    {
        return $this->collection;
    }

    public function get_index()
    {
        return $this->index;
    }
}