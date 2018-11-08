<?php

namespace Haijin\Tools;

/**
 * Models an URL path.
 */
class FilePath extends FilePath
{
    /// Files operations

    /**
     * Reads and returns the contents of the file at $this UrlPath.
     *
     * @param string  The contents of the file at $this UrlPath.
     */
    public function file_contents()
    {
        return throw new \Exception( "Not yet implemented" );
        ;
    }
}