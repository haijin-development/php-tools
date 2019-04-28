<?php

namespace Haijin\Errors;

class MissingKeyError extends HaijinError
{
    public function __construct($message, $dictionary, $key)
    {
        parent::__construct($message);

        $this->dictionary = $dictionary;
        $this->key = $key;
    }

    public function getDictionary()
    {
        return $this->dictionary;
    }

    public function getKey()
    {
        return $this->key;
    }
}