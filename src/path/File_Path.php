<?php

namespace Haijin;

/**
 * Models a file path.
 *
 * Examples:
 *
 *      /// Creating paths
 *
 *      use Haijin\File_Path;
 *
 *      // Creates an empty path.
 *      $path = new File_Path();
 *
 *      // Creates a path from an attributes chain string.
 *      $path = new File_Path( 'home/dev/src' );
 *      print( $path . "\n" );
 *
 *      // Creates a path from an attributes chain array.
 *      $path = new File_Path( ['home', 'dev', 'src'] );
 *      print( $path . "\n" );
 *
 *      // Creates a path from another path.
 *      $path = new File_Path( new File_Path( 'home/dev/src' ) );
 *      print( $path . "\n" );
 *
 *      /// Concatenating paths
 *
 *      // Concatenates two paths into a new one.
 *      $path = new File_Path( 'home' );
 *      $new_path = $path->concat( new File_Path( 'dev/src' ) );
 *      print( $new_path . "\n" );
 *
 *      // Concatenates a string into a new File_Path.
 *      $path = new File_Path( 'home' );
 *      $new_path = $path->concat( 'dev/src' );
 *      print( $new_path . "\n" );
 *
 *      // Concatenates an array of attributes into a new File_Path.
 *      $path = new File_Path( 'home' );
 *      $new_path = $path->concat( ['dev/src'] );
 *      print( $new_path . "\n" );
 *
 *      /// Moving the path back
 *
 *      // Removes the last attribute from the path into a new path.
 *      $path = new File_Path( 'home/dev/src' );
 *      $new_path = $path->back();
 *      print( $new_path . "\n" );
 *      
 *      // Removes the last n attributes from the path into a new path.
 *      $path = new File_Path( 'home/dev/src' );
 *      $new_path = $path->back( 2 );
 *      print( $new_path . "\n" );
 *
 *      /// Appending paths
 *
 *      // Appends another Path to a path.
 *      $path = new File_Path( 'home' );
 *      $path->append( new File_Path( 'dev/src' ) );
 *      print( $path . "\n" );
 *
 *      // Appends an attributes string to a path.
 *      $path = new File_Path( 'home' );
 *      $path->append( 'dev/src' );
 *      print( $path . "\n" );
 *
 *      // Appends an attributes array to a path.
 *      $path = new File_Path( 'home' );
 *      $path->append( ['dev/src'] );
 *      print( $path . "\n" );
 *
 *      /// Dropping path tails
 *
 *      // Drops the last attribute from the path.
 *      $path = new File_Path( 'home/dev/src' );
 *      $path->drop();
 *      print( $path . "\n" );
 *
 *      // Drops the last n attributes from the path.
 *      $path = new File_Path( 'home/dev/src' );
 *      $path->drop( 2 );
 *      print( $path . "\n" );
 *
 */
class File_Path extends Path
{
    /**
     * Flags if $this File_Path is absolute or relative.
     */
    protected $is_absolute;

    /**
     * Some operative sistems begins with a protocol before the path (ej. C:/).
     */
    protected $protocol;

    /**
     * Initializes the instance
     */
    public function __construct($attributes_chain = null)
    {
        parent::__construct( $attributes_chain );

        $length = $this->length();
        if( $length > 0 && $this->path[ $length - 1 ] == "" ) {
            $this->path = array_slice( $this->path, 0, $length - 1 );
        }

        $this->protocol = null;
        $this->is_absolute = false;

        if( method_exists( $attributes_chain, "is_absolute" ) ) {
            $this->is_absolute = $attributes_chain->is_absolute;
        } else {
            $this->normalize_absolute_path();
        }
    }

    /**
     * If the $attributes_path begins with a separtor ("/") the path is considered an absolute path.
     * Set the $this->is_absolute accordingly and remove the separator from the attribute path
     */
    protected function normalize_absolute_path()
    {
        if( empty( $this->path ) )
            return;

        if( $this->path[ 0 ] != '' ) {
            if( $this->path[ 0 ][ 0 ] == $this->separator ) {
                $this->is_absolute = true;
                $this->path[ 0 ] = substr( $this->path[ 0 ], 1 );
            }
        } else {
            $this->is_absolute = true;
            $this->path = array_slice( $this->path, 1 );
        }
    }

    /// Constants

     /**
     * Returns the string used as a separator between consecutive attributes when converting the Path 
     * to a string.
     *
     * @return string The string used as a separator between consecutive attributes when converting the Path 
     * to a string.
     */
    public function default_separator()
    {
        return '/';
    }

    public function be_absolute( $is_absolute = true )
    {
        $this->is_absolute = $is_absolute;

        return $this;
    }

    public function be_relative( $is_relative = true )
    {
        return $this->be_absolute( !$is_relative );
    }

    /// Appending and dropping attributes

    /**
     * Concatenates attributes to the path.
     *
     * Returns a new Attribute_Path with the appended path.
     *
     * @param string|array|Path $attributes_chain The attributes to concatenate to $this object.
     *
     * @return File_Path A new Path object with the $attributes_chain concatenated.
     */
    public function concat($attributes_chain)
    {
        $new_path = parent::concat( $attributes_chain );
        $new_path->be_absolute( $this->is_absolute );

        return $new_path;
    }

    /**
     * Creates a new Path object removing the last attribute from the Path.
     *
     * If an integer $n is passed removes the last $n attributes from $this Path.
     *
     * Returns a new Path with the last attributes removed.
     *
     * @param integer $n Optional - An integer >= 0 to move back in the attributes chain.
     *
     * @return File_Path A new Path with the last attributes removed.
     */
    public function back($n = 1)
    {
        $new_path = parent::back( $n );
        $new_path->be_absolute( $this->is_absolute );

        return $new_path;
    }

    /// Querying

    /**
     * Returns the name of the file. Assumes the file name is the ending part of the path.
     *
     * @return string The name of the file at the end of the path.
     */
    public function file_name()
    {
        return $this->get_last_attribute();
    }

    /**
     * Returns the extension of the file. Assumes the file name is the ending part of the path.
     *
     * @return string The name of the file at the end of the path.
     */
    public function file_extension()
    {
        $parts = explode( '.', $this->file_name() );

        if( count( $parts ) == 0 )
            return "";

        return $parts[ count( $parts ) - 1 ];
    }

    /**
     * Returns the protocol of the file.
     *
     * @return string The protocol of the file.
     */
    public function protocol()
    {
        return $protocol;
    }

    /**
     * Returns the last modification time of the file.
     *
     * @return string The protocol of the file.
     */
    public function file_modification_time()
    {
        return filemtime( $this->to_string() );
    }

    /// Asking

    /**
     * Returns true if $this File_Path is absolute, false if its relative.
     *
     * @return bool Returns true if $this File_Path is absolute, false if its relative.
     */
    public function is_absolute()
    {
        return $this->is_absolute;
    }

    /**
     * Returns false if $this File_Path is relative, false if its absolute.
     *
     * @return bool Returns false if $this File_Path is relative, false if its absolute.
     */
    public function is_relative()
    {
        return !$this->is_absolute();
    }

    /// Displaying

    /**
     * Returns the path as a string of attributes separated by dots.
     *
     * @param string $separator Optional - The string used between consecutives attributes. Defaults to ".".
     *
     * @return string The attributes path string.
     */
    public function to_string($separator = null)
    {
        if( $separator === null )
            $separator = $this->separator;

        if( $this->is_absolute ) {
            return $separator . parent::to_string( $separator );

        } else {
            return parent::to_string( $separator );
        }
    }

    /// Files operations

    public function exists_file()
    {
        return file_exists( $this->to_string() ) && ! is_dir( $this->to_string() );
    }

    public function exists_folder()
    {
        return is_dir( $this->to_string() );
    }

    /**
     * Reads and returns the contents of the file at $this File_Path.
     *
     * @return string  The contents of the file at $this File_Path.
     */
    public function file_contents()
    {
        return file_get_contents( $this->to_string() );
    }

    /**
     * Writes the contents to the file at $this File_Path.
     *
     * @param string  The contents to write to the file at $this File_Path.
     */
    public function write_contents($contents)
    {
        file_put_contents( $this->to_string(), $contents );
    }

    /**
     * Recursively creates a subfolder tree from $this File_Path.
     */
    public function create_folder_path($permissions = 0777)
    {
        mkdir( $this->to_string(), $permissions, true );
    }

    /**
     * Deletes the file or the folder with all of its contents.
     */
    public function delete()
    {
        if( $this->exists_folder() ) {

            $this->delete_folder();

        } elseif( $this->exists_file() ) {

            $this->delete_file();

        }
    }

    /**
     * Deletes the file.
     */
    public function delete_file()
    {
        unlink( $this->to_string() );
    }

    /**
     * Recursively deletes the folder and its contents.
     */
    public function delete_folder()
    {
        foreach( $this->get_folder_contents() as $file_or_folder ) {

            $file_or_folder->delete();

        }

        if( $this->exists_folder() ) {
            rmdir( $this->to_string() );
        }
    }

    /**
     * Returns the contents of the folder with the given pattern.
     * If no pattern is given returns all the files and folders that are direct
     * children from $this folder.
     */
    public function get_folder_contents($search_pattern = "*")
    {
        $files = glob( $this->concat( $search_pattern )->to_string() );

        $file_paths = [];

        foreach( $files as $file ) {
            $file_paths[] = new self( $file );
        }

        return $file_paths;
    }
}