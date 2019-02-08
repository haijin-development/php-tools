<?php

namespace Haijin;

class Missing_Cached_File_Error extends \RuntimeException
{
    protected $source_filename;

    public function __construct($message, $source_filename)
    {
        parent::__construct( $message );

        $this->source_filename = $source_filename;
    }

    public function get_source_filename()
    {
        return $this->source_filename;
    }
}