<?php

namespace Haijin\Errors;

class CouldNotCreateDirectoryError extends HaijinError
{
    protected $directory;

    /// Initializing

    public function __construct($message, $directory)
    {
        parent::__construct($message);

        $this->directory = $directory;
    }

    /// Accessing

    public function getFolder()
    {
        return $this->directory;
    }
}