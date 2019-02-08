<?php

namespace Haijin;


class Files_Cache
{
    protected $cache_folder;
    protected $manifest_filename;
    protected $manifest;

    /// Initializing

    public function __construct()
    {
        $this->cache_folder = null;
        $this->manifest_filename = null;
        $this->manifest = null;
    }

    /// Accessing

    public function get_cache_folder()
    {
        if( $this->cache_folder !== null ) {
            return $this->cache_folder->to_string();
        }

        return null;
    }

    public function set_cache_folder($folder)
    {
        $this->cache_folder = new File_Path( $folder );

        $this->set_manifest_filename(
            $this->cache_folder->concat( "cached_file_manifest.json" )
        );

        return $this;
    }

    public function get_manifest_filename()
    {
        if( $this->manifest_filename !== null ) {
            return $this->manifest_filename->to_string();
        }

        return null;
    }

    public function set_manifest_filename($filename)
    {
        $this->manifest_filename = new File_Path( $filename );

        return $this;
    }

    /// Caching

    public function cache_file_contents($source_filename, $contents, $filename)
    {
        $this->validate_lock();

        $cached_filename = $this->cache_folder->concat( $filename );

        $cached_filename->write_contents( $contents );

        $this->manifest->at_put( $source_filename, $cached_filename );

        $this->manifest->write();
    }

    public function cache_file($source_filename, $filename)
    {
        $this->validate_lock();

        $cached_filename = $this->cache_folder->concat( $filename );

        copy( $source_filename, $cached_filename->to_string() );

        $this->manifest->at_put( $source_filename, $cached_filename );

        $this->manifest->write();
    }

    /// Querying

    public function needs_caching($source_file)
    {
        $this->validate_lock();

        return $this->manifest->needs_caching( $source_file );
    }

    public function get_path_of( $source_file )
    {
        $this->validate_lock();

        return $this->manifest->get_cached_path_of( $source_file );
    }

    /// Locking

    public function locking_do($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        $this->manifest = $this->get_manifest();

        $this->manifest->lock();

        try {

            $this->manifest->read();

            return $closure->call( $binding, $this );

        } finally {

            $this->manifest->unlock();

            $this->manifest = null;

        }
    }

    protected function validate_lock()
    {
        if( $this->manifest !== null ) {
            return;
        }

        $this->raise_missing_locking_error();
    }

    protected function raise_missing_locking_error()
    {
        throw new \RuntimeException(
            "To perform this operation must adquire a lock with 'locking_do(\$closure)'."
        );
    }

    protected function get_manifest()
    {
        return new Files_Cache_Manifest( $this->manifest_filename );
    }
}