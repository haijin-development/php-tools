<?php

namespace Haijin;

class Missing_Key_Error extends \RuntimeException
{
    public function __construct($message, $dictionary, $key)
    {
        parent::__construct( $message );

        $this->dictionary = $dictionary;
        $this->key = $key;
    }

    public function get_dictionary()
    {
        return $this->dictionary;
    }

    public function get_key()
    {
        return $this->key;
    }
}