<?php

namespace Haijin\Tools;

/**
 * Models a path of attributes to access nested attributes from a root object.
 */
abstract class Path
{
    /**
     * An array with each single attribute that, chained, will get from the root object to the bottom attribute.
     */
    protected $path;

    /**
     * The string that separates two consecutive items in the path when represented as a string.
     * For instance '.' or '/'.
     * The actual separator used is defined by each concrete subclass.
     */
    protected $separator;

    /// Initializing

    /**
     * Initializes a new Path. Optionaly accepts an $attributes_chain.
     *
     * The $attributes_chain can be an attributes string (ej. 'address.street'), an attributes 
     * array (ej. [ 'address', 'street' ]) or another Path object.
     *
     * @param string|array|Path $attributes_chain The attributes to initialize $this object with.
     */
    public function __construct($attributes_chain = null)
    {
        $this->path = [];
        $this->separator = $this->default_separator();

        if( $attributes_chain != null )
            $this->path = $this->normalize_attributes_chain( $attributes_chain );
    }

    /**
     * Takes a string, array or Path and converts it to an array of single attributes.
     *
     * @param string|array|Path $attributes_chain The parameter to normalize.
     *
     * @return array The array of single attributes obtained from normalizing the $attributes_chain parameter.
     */
    protected function normalize_attributes_chain($attributes_chain)
    {
        if( is_array( $attributes_chain ) )
            return $attributes_chain;

        if( is_string( $attributes_chain ) ){
            if( $attributes_chain == '' ) return [];
            return explode( $this->separator, $attributes_chain );
        }

        return $attributes_chain->to_array();        
    }

    /// Constants

    /**
     * Returns the string used as a separator between consecutive attributes when converting the Path 
     * to a string. For instance '.' for attribute paths or '/' for file paths.
     *
     * @return string The string used as a separator between consecutive attributes when converting the Path 
     * to a string.
     */
    public abstract function default_separator();

    /// Querying

    /**
     * Returns the length of $this path.
     *
     * @return integer The length of the path.
     */
    public function length()
    {
        return count( $this->path );
    }

    /**
     * Returns the last attribute in the path.
     *
     * @return string The last attribute in the path.
     */
    public function get_last_attribute()
    {
        $count = count( $this->path );

        if( $count == 0 ) {
            return "";
        }

        return $this->path[ $count - 1 ];
    }

    /// Appending and dropping attributes

    /**
     * Concatenates attributes to the path.
     *
     * Returns a new AttributePath with the appended path.
     *
     * @param string|array|Path $attributes_chain The attributes to concatenate to $this object.
     *
     * @return A new Path object with the $attributes_chain concatenated.
     */
    public function concat($attributes_chain)
    {
        $new_path = $this->new_instance_with( $this );
        return $new_path->append( $attributes_chain );
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
     * @return Path A new Path with the last attributes removed.
     */
    public function back($n = 1)
    {
        if( $n < 0 ) {
            $class = get_class( $this );
            return $this->raise_path_error( "{$class}->back( {$n} ): invalid parameter {$n}." );
        }

        $new_path = $this->new_instance_with( $this->to_array() );
        return $new_path->drop( $n );
    }

    /**
     * Drops the last attribute from the Path.
     *
     * If an integer $n is passed removes the last $n attributes from $this Path.
     *
     * Returns $this object.
     *
     * @param integer $n Optional - An integer >= 0 to move back in the attributes chain.
     *
     * @return Path $this object with the last attributes dropped.
     */
    public function drop($n = 1)
    {
        if( $n < 0 ) {
            $class = get_class( $this );
            return $this->raise_path_error( "{$class}->drop( {$n} ): invalid parameter {$n}." );
        }

        if( $n > 0 ) 
            $this->path = array_slice( $this->path, 0, -$n );

        return $this;
    }

    /**
     * Appends attributes to the path. Modifies the path.
     *
     * Returns $this object to allow chaining calls.
     *
     * @param string|array|Path $attributes_chain The attributes to append to $this object.
     *
     * @return Path Returns $this object.
     */
    public function append($attributes_chain)
    {
        if( ! method_exists( $attributes_chain, "to_array" ) ) {
            $attributes_chain = $this->new_instance_with( $attributes_chain );
        }

        $this->path = array_merge( $this->path, $attributes_chain->to_array() );

        return $this;
    }

    /// Converting

    /**
     * Returns the path as an array of single attributes.
     *
     * @return array<string> The array of the attributes in the path.
     */
    public function to_array()
    {
        return $this->path;
    }

    /**
     * Returns the path as a string of attributes separated by dots.
     *
     * @param string $separator Optional - The string used between consecutives attributes. Defaults to ".".
     *
     * @return string The attributes path string.
     */
    public function to_string($separator = null)
    {
        if( $separator == null ) {
            $separator = $this->separator;
        }

        return join( $separator, $this->path);
    }

    /// Printing

    /**
     * Prints a text representation of $this object.
     *
     * @return string The string representation of $this object.
     */
    public function __toString()
    {
        return $this->to_string();
    }

    /// Errors

    /**
     * Raises a new PathError with a $message.
     *
     * The raising an error is a method on its own to allow subclasses to override it if they want to.
     *
     * @param string $message The error message.
     */
    protected function raise_path_error($message)
    {
        throw $this->new_path_error($message);
    }

    /**
     * Creates a new PathError object with a $message.
     *
     * The creation of an object is a method on its own to allow subclasses to override it with their
     * own Error class if they want to.
     *
     * @param string $message The error message.
     *
     * @return PathError A new ErrorPath object.
     */
    protected function new_path_error($message)
    {
        return new PathError($message);
    }

    /**
     * Creates a new instance of the same class of $this initialized with an $attributes_chain.
     *
     * @param string|array|Path $attributes_chain The attributes to initialize the new Path object with.
     *
     * @return Path A new Path initialized with the $attributes_chain.
     */
    protected function new_instance_with( $attributes_chain )
    {
        $class = get_class( $this );
        return new $class( $attributes_chain );
    }
}