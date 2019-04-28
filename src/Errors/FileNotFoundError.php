<?php

namespace Haijin\Errors;

class FileNotFoundError extends HaijinError
{
    protected $filename;

    /// Initializing

    public function __construct($message, $filename)
    {
        parent::__construct($message);

        $this->filename = $filename;
    }


    /// Accessing

    public function getFilename()
    {
        return $this->filename;
    }
}