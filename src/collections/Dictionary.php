<?php

namespace Haijin\Tools;

/**
 * An alternative to using PHP arrays for associative collections.
 * It is always passed by reference and has a consistent, simple and complete protocol.
 */
class Dictionary implements \ArrayAccess
{
    /// Class methods

        /// Creating

    /**
     * Creates and returns a new Dictionary with an association in it.
     *
     * Example
     *
     *      $dictionary = Dictionary::with( 'a', 123 );
     *
     * @param object $key A key in the dictionary.
     * @param object $value A value associated to the key.
     *
     * @return Dictionary The created Dictionary with the association in it.
     */
    static public function with($key, $value)
    {
        return self::with_all( [ $key => $value ] );
    }

    /**
     * Creates and returns a new Dictionary with all the associations in it.
     *
     * Example
     *
     *      $dictionary = Dictionary::with_all( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
     *
     * @param array|Dictionary $associations The associations to add to the created dictionary.
     *
     * @return Dictionary The Dictionary with the associations in it.
     */
    static public function with_all($associations)
    {
        return new self( $associations );
    }

    /// Instance methods

    /**
     * The actual associations array.
     */
    protected $associations;

    /**
     * Initializes $this object with an optional array of associations in it.
     *
     * @param array|Dictionary $items Optional - An array or Dictionary with associations
     *      to add to $this Dictionary.
     */
    public function __construct($associations = [])
    {
        $this->associations = [];

        $this->merge_with( $associations );
    }

    /// Asking

    /**
     * Returns true if $this Dictionary is empty, false otherwise.
     *
     *  Example
     *
     *      $dictionary->is_empty(); // => true or false
     *
     * @return bool Returns true if $this dictionary is empty, false otherwise.
     */
    public function is_empty()
    {
        return $this->size() == 0;
    }

    /**
     * Returns true if $this dictionary is not empty, false otherwise.
     *
     *  Example
     *
     *      $dictionary->not_empty(); // => true or false
     *
     * @return bool Returns true if $this dictionary is not empty, false otherwise.
     */
    public function not_empty()
    {
        return ! $this->is_empty();
    }

    /**
     * Returns true if $this dictionary has a key defined, false if not.
     *
     * @return bool Returns true if $this dictionary has a key defined, false if not.
     */
    public function has_key($key)
    {
        return array_key_exists( $key, $this->associations );
    }

    /**
     * Returns true if $this dictionary has not a key defined, false if not.
     *
     * @return bool Returns true if $this dictionary has not a key defined, false if not.
     */
    public function no_key($key)
    {
        return ! $this->has_key();
    }

    /// Accessing items

    /**
     * Returns an array with $this Dictionary keys.
     *
     *  Example
     *
     *      $item = $dictionary->get_keys();
     *
     * @return array An array with the keys of this dictionary.
     */
    public function get_keys()
    {
        return array_keys( $this->associations );
    }

    /**
     * Returns an array with $this Dictionary values.
     *
     *  Example
     *
     *      $item = $dictionary->get_values();
     *
     * @return array An array with the values of this dictionary.
     */
    public function get_values()
    {
        return array_values( $this->associations );
    }

    /**
     * Returns the value at the $key.
     *
     *  Example
     *
     *      $item = $dictionary->at( 'a' );
     *
     * @param object $key The key to look for.
     *
     * @return object The value at the given key.
     */
    public function at($key)
    {
        return $this->at_if_absent( $key, function($dictionary, $key) {
            $this->raise_missing_key_error($key);
        });
    }

    /**
     * Returns the value at the $key. If the key not defined, evaluates the $absent_closure.
     *
     *  Example
     *
     *      $value = $dictionary->at_if_absent( 'a', function(){
     *          return "A default value";
     *      });
     *
     * @param object $key The key to look for.
     * @param closure $absent_closure The closure to evaluate if the key is not defined.
     * @param object $binding Optional - An optional binding for the evaluation of the $absent_closure.
     *
     * @return object The item at the given position or the result of evaluating the $absent_closure.
     */
    public function at_if_absent($key, $absent_closure, $binding = null)
    {
        if( ! $this->has_key($key) ) {
            if( $binding === null ){
                $binding = $this;
            }

            if( is_callable( $absent_closure ) ) {
                return $absent_closure->call( $binding, $this, $key );
            } else {
                return $absent_closure;
            }
        }

        return $this->associations[ $key ];
    }

    /**
     * Puts a value at a key.
     *
     *  Example
     *
     *      $dictionary->at_put( 'key', 123 );
     *
     * @param object $key The key to associate the value with.
     * @param object $value The value to put in the given $key.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function at_put($key, $value)
    {
        $this->associations[ $key ] = $value;

        return $this;
    }

    /**
     * Merges the associations of a Dictionary or array into $this Dictionary.
     *
     *  Example
     *
     *      $dictionary->merge_with( [ 'key', 123 ] );
     *      $dictionary->merge_with( $another_dictionary );
     *
     * @param array|Dictionary $dictionary The associations to merge into $this dictionary.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function merge_with($associations)
    {
        if( method_exists( $associations, 'to_array' ) ) {
            $associations = $associations->to_array();
        }

        $this->associations = array_merge( $this->associations, $associations );

        return $this;
    }

    /// Removing associations

    /**
     * Removes the association at a key.
     *
     *  Example
     *
     *      $value = $ordered_collection->remove_at( 'a' );
     *
     * @param object $key The key of the association to remove.
     *
     * @return object The value of the association removed.
     */
    public function remove_at($key)
    {
        return $this->remove_at_if_absent( $key, function($dictionary, $key) {
            return $this->raise_missing_key_error( $key );
        });
    }

    /**
     * Removes the association at a key. If the key is not defined evaluates the absent closure.
     *
     *  Example
     *
     *      $value = $ordered_collection->remove_at( 'a', function() {
     *          return "absent value";
     *      });
     *
     * @param object $key The key of the association to remove.
     * @param callable $absent_closure A closure to evaluate of the key to remove is not defined.
     * @param object $binding An optional object to bind to the absent_closure.
     *
     * @return object The value of the association removed, or if the key is not defined the
     *      result of evaluating the absent closure.
     */
    public function remove_at_if_absent($key, $absent_closure, $binding = null)
    {
        if( ! $this->has_key($key) ) {
            if( $binding === null ) {
                $binding = $this;
            }

            if( is_callable( $absent_closure ) ) {
                return $absent_closure->call( $binding, $this, $key );
            } else {
                return $absent_closure;
            }
        }

        $value = $this->associations[ $key ];

        unset( $this->associations[ $key ] );

        return $value;
    }

    /// Querying

    /**
     * Returns the number of associations $this dictionary has.
     *
     * @return int The number of associations $this dictionary has.
     */
    public function size()
    {
        return count( $this->associations );
    }


    /// Iterating

    /**
     * Evaluates a closure on each (key, value) association in $this dictionary.
     * Returns $this object.
     *
     *  Example
     *
     *      $dictionary->keys_and_values_do( function($key, $value) {
     *          print $key;
     *          print $value;
     *      }, $this);
     *
     * @param callable $closure A closure that is evaluated on each key and value pair in the dictionary.
     * @param object $binding An optional binding for the closure.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function keys_and_values_do($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        foreach( $this->associations as $key => $value ) {
            $closure->call( $binding, $key, $value );
        }

        return $this;
    }

    /**
     * Evaluates a closure on each key in $this dictionary.
     * Returns $this object.
     *
     *  Example
     *
     *      $dictionary->keys_do( function($key) {
     *          print $key;
     *      }, $this);
     *
     * @param callable $closure A closure that is evaluated on each key in $this dictionary.
     * @param object $binding An optional binding for the closure.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function keys_do($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        foreach( $this->associations as $key => $value ) {
            $closure->call( $binding, $key );
        }

        return $this;
    }

    /**
     * Evaluates a closure on each value in $this dictionary.
     * Returns $this object.
     *
     *  Example
     *
     *      $dictionary->values_do( function($value) {
     *          print $value;
     *      }, $this);
     *
     * @param callable $closure A closure that is evaluated on each value in $this dictionary.
     * @param object $binding An optional binding for the closure.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function values_do($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        foreach( $this->associations as $key => $value ) {
            $closure->call( $binding, $value );
        }

        return $this;
    }

    /// Converting

    /**
     * Returns an array with the items in $this Dictionary.
     * This is not a getter on the internal array. A copy is returned.
     *
     * @return array Returns an array with the items in $this Dictionary.
     */
    public function to_array()
    {
        return array_slice( $this->associations, 0 );
    }

    /**
     * Returns a string representation of $this collection.
     *
     * @return string A string representation of $this collection.
     */
    public function to_string()
    {
        $s = join( ', ', $this->associations );
        return "Dictionary::with_all( [{$s}] )";
    }

    /**
     * Returns a string representation of $this collection.
     *
     * @return string A string representation of $this collection.
     */
    public function __toString()
    {
        return $this->to_string();
    }

    /// Errors

    /**
     * Raises a MissingKeyError.
     */
    protected function raise_missing_key_error($key)
    {
        throw new MissingKeyError( "The key '{$key}' is not defined.", $this, $key );
    }

    /// ArrayAccess implementation
    
    public function offsetExists( $offset )
    {
        return $this->index_is_in_range( $offset );
    }

    public function offsetGet( $offset )
    {
        return $this->at( $offset );
    }

    public function offsetSet( $offset , $value )
    {
        return $this->at_put( $offset, $value );
    }

    public function offsetUnset( $offset )
    {
        return $this->remove_at( $offset );
    }
}