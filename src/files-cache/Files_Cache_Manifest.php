<?php

namespace Haijin;


class Files_Cache_Manifest
{
    protected $filepath;
    protected $cached_files;
    protected $file_handle;

    /// Initializing

    public function __construct($filename)
    {
        $this->filepath = new File_Path( $filename );
        $this->cached_files = new Dictionary();
        $this->file_handle = null;

        $this->ensure_manifest_file_exists();
    }

    /// Locking

    public function lock()
    {
        $this->file_handle = fopen( $this->filepath->to_string(), "r+" );

        if( $this->file_handle === false ) {
            throw new \Exception( "Unable to open manifest file." );
        }

        if( flock( $this->file_handle, LOCK_EX ) === false ) {
            throw new \Exception( "Unable to lock manifest file." );
        }
    }

    public function unlock()
    {
        flock( $this->file_handle, LOCK_UN );
        fclose( $this->file_handle );
    }

    /// Writting

    public function at_put($source_filename, $filename)
    {
        $source_filename = new File_Path( $source_filename );
        $filepath = new File_Path( $filename );

        $this->cached_files->at_put(
            $source_filename->to_string(),
            [
                "filepath" => $filepath->to_string(),
                "modification_time" => $source_filename->file_modification_time(),
                "cached_at" => time()
            ]
        );
    }

    public function write()
    {
        $this->ensure_manifest_folder_exists();

        $this->filepath->write_contents(
            json_encode( $this->cached_files->to_array() )
        );

        return $this;        
    }

    protected function ensure_manifest_folder_exists()
    {
        if( ! $this->exists_manifest_folder() ) {
            $this->create_manifest_folder();
        }
    }

    protected function ensure_manifest_file_exists()
    {
        if( ! $this->filepath->exists_file() ) {
            $this->create_file_manifest();
        }
    }

    /// Reading

    public function get_cached_path_of($source_filename)
    {
        return $this->cached_files->at_if_absent( $source_filename, function()
                                                                use($source_filename) {

            $this->raise_missing_cached_file_error( $source_filename );

        }, $this )[ "filepath" ];
    }

    public function read()
    {
        $this->cached_files = Dictionary::with_all(
            json_decode( $this->filepath->file_contents(), true )
        );

        return $this;        
    }

    protected function create_file_manifest()
    {
        $this->write();
    }

    /// Asking

    public function needs_caching($source_filename)
    {
        return ! $this->is_cached( $source_filename ) || $this->is_outdated( $source_filename );
    }

    public function is_outdated($source_filename)
    {
        $source_path = new File_Path( $source_filename );

        return $this->cached_files[ $source_filename ][ "modification_time" ]
            <
            $source_path->file_modification_time();
    }

    public function is_cached($source_filename)
    {
        return $this->cached_files->has_key( $source_filename );
    }

    protected function exists_manifest_folder()
    {
        return $this->filepath->back()->exists_folder();
    }

    protected function create_manifest_folder()
    {
        mkdir( $this->filepath->back()->to_string(), 0777, true );
    }

    /// Raising errors

    protected function raise_missing_cached_file_error($source_filename)
    {
        throw new Missing_Cached_File_Error(
            "The file \"$source_filename\" was not found in the cache folder.",
            $source_filename
        );
    }
}