<?php

namespace Haijin;

use ArrayAccess;
use Haijin\Errors\MissingKeyError;

/**
 * An alternative to using PHP arrays for associative collections.
 * It is always passed by reference and has a consistent, simple and complete protocol.
 */
class Dictionary implements ArrayAccess
{
    /// Class methods

    /// Creating

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

        $this->mergeWith($associations);
    }

    /// Instance methods

    /**
     * Merges the associations of a Dictionary or array into $this Dictionary.
     *
     *  Example
     *
     *      $dictionary->mergeWith( [ 'key' => 123 ] );
     *      $dictionary->mergeWith( $anotherDictionary );
     *
     * @param array|Dictionary $dictionary The associations to merge into $this dictionary.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function mergeWith($associations)
    {
        if (method_exists($associations, 'toArray')) {
            $associations = $associations->toArray();
        }

        $this->associations = array_merge($this->associations, $associations);

        return $this;
    }

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
        return self::withAll([$key => $value]);
    }

    /// Asking

    /**
     * Creates and returns a new Dictionary with all the associations in it.
     *
     * Example
     *
     *      $dictionary = Dictionary::withAll( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
     *
     * @param array|Dictionary $associations The associations to add to the created dictionary.
     *
     * @return Dictionary The Dictionary with the associations in it.
     */
    static public function withAll($associations)
    {
        return new self($associations);
    }

    /**
     * Returns true if $this dictionary is not empty, false otherwise.
     *
     *  Example
     *
     *      $dictionary->notEmpty(); // => true or false
     *
     * @return bool Returns true if $this dictionary is not empty, false otherwise.
     */
    public function notEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * Returns true if $this Dictionary is empty, false otherwise.
     *
     *  Example
     *
     *      $dictionary->isEmpty(); // => true or false
     *
     * @return bool Returns true if $this dictionary is empty, false otherwise.
     */
    public function isEmpty()
    {
        return $this->size() == 0;
    }

    /**
     * Returns the number of associations $this dictionary has.
     *
     * @return int The number of associations $this dictionary has.
     */
    public function size()
    {
        return count($this->associations);
    }

    /// Accessing items

    /**
     * Returns true if $this dictionary has not a key defined, false if not.
     *
     * @return bool Returns true if $this dictionary has not a key defined, false if not.
     */
    public function noKey($key)
    {
        return !$this->hasKey($key);
    }

    /**
     * Returns true if $this dictionary has a key defined, false if not.
     *
     * @return bool Returns true if $this dictionary has a key defined, false if not.
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->associations);
    }

    /**
     * Returns an array with $this Dictionary keys.
     *
     *  Example
     *
     *      $item = $dictionary->getKeys();
     *
     * @return array An array with the keys of this dictionary.
     */
    public function getKeys()
    {
        return array_keys($this->associations);
    }

    /**
     * Returns an array with $this Dictionary values.
     *
     *  Example
     *
     *      $item = $dictionary->getValues();
     *
     * @return array An array with the values of this dictionary.
     */
    public function getValues()
    {
        return array_values($this->associations);
    }

    /**
     * Evaluates a callable on each (key, value) association in $this dictionary.
     * Returns $this object.
     *
     *  Example
     *
     *      $dictionary->keysAndValuesDo( function($key, $value) {
     *          print $key;
     *          print $value;
     *      });
     *
     * @param callable $callable A callable that is evaluated on each key and value pair in the dictionary.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function keysAndValuesDo($callable)
    {
        foreach ($this->associations as $key => $value) {
            $callable($key, $value);
        }

        return $this;
    }

    /**
     * Evaluates a callable on each key in $this dictionary.
     * Returns $this object.
     *
     *  Example
     *
     *      $dictionary->keysDo( function($key) {
     *          print $key;
     *      });
     *
     * @param callable $callable A callable that is evaluated on each key in $this dictionary.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function keysDo($callable)
    {
        foreach ($this->associations as $key => $value) {
            $callable($key);
        }

        return $this;
    }

    /// Removing associations

    /**
     * Evaluates a callable on each value in $this dictionary.
     * Returns $this object.
     *
     *  Example
     *
     *      $dictionary->valuesDo( function($value) {
     *          print $value;
     *      });
     *
     * @param callable $callable A callable that is evaluated on each value in $this dictionary.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function valuesDo($callable)
    {
        foreach ($this->associations as $key => $value) {
            $callable($value);
        }

        return $this;
    }

    /**
     * Returns an array with the items in $this Dictionary.
     * This is not a getter on the internal array. A copy is returned.
     *
     * @return array Returns an array with the items in $this Dictionary.
     */
    public function toArray()
    {
        return $this->associations;
    }

    /// Querying

    public function offsetExists($offset)
    {
        return isset($this->associations[$offset]);
    }


    /// Iterating

    public function offsetGet($offset)
    {
        return $this->at($offset);
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
        return $this->atIfAbsent($key, function ($dictionary, $key) {
            $this->raiseMissingKeyError($key);
        });
    }

    /**
     * Returns the value at the $key. If the key not defined, evaluates the $absentCallable.
     *
     *  Example
     *
     *      $value = $dictionary->atIfAbsent( 'a', function(){
     *          return "A default value";
     *      });
     *
     * @param object $key The key to look for.
     * @param callable $absentCallable The callable to evaluate if the key is not defined.
     *
     * @return object The item at the given position or the result of evaluating the $absentCallable.
     */
    public function atIfAbsent($key, $absentCallable)
    {
        if (!$this->hasKey($key)) {

            if (is_callable($absentCallable)) {
                return $absentCallable($this, $key);
            } else {
                return $absentCallable;
            }

        }

        return $this->associations[$key];
    }

    /// Converting

    /**
     * Raises a Missing_Key_Error.
     */
    protected function raiseMissingKeyError($key)
    {
        throw new MissingKeyError("The key '{$key}' is not defined.", $this, $key);
    }

    /// Errors

    public function offsetSet($offset, $value)
    {
        return $this->atPut($offset, $value);
    }

    /// ArrayAccess implementation

    /**
     * Puts a value at a key.
     *
     *  Example
     *
     *      $dictionary->atPut( 'key', 123 );
     *
     * @param object $key The key to associate the value with.
     * @param object $value The value to put in the given $key.
     *
     * @return Dictionary Returns $this dictionary.
     */
    public function atPut($key, $value)
    {
        $this->associations[$key] = $value;

        return $this;
    }

    public function offsetUnset($offset)
    {
        return $this->removeAt($offset);
    }

    /**
     * Removes the association at a key.
     *
     *  Example
     *
     *      $value = $orderedCollection->removeAt( 'a' );
     *
     * @param object $key The key of the association to remove.
     *
     * @return object The value of the association removed.
     */
    public function removeAt($key)
    {
        return $this->removeAtIfAbsent($key, function ($dictionary, $key) {
            return $this->raiseMissingKeyError($key);
        });
    }

    /**
     * Removes the association at a key. If the key is not defined evaluates the absent callable.
     *
     *  Example
     *
     *      $value = $orderedCollection->removeAt( 'a', function() {
     *          return "absent value";
     *      });
     *
     * @param object $key The key of the association to remove.
     * @param callable $absentCallable A callable to evaluate of the key to remove is not defined.
     *
     * @return object The value of the association removed, or if the key is not defined the
     *      result of evaluating the absent callable.
     */
    public function removeAtIfAbsent($key, $absentCallable)
    {
        if (!$this->hasKey($key)) {

            if (is_callable($absentCallable)) {
                return $absentCallable($this, $key);
            } else {
                return $absentCallable;
            }

        }

        $value = $this->associations[$key];

        unset($this->associations[$key]);

        return $value;
    }
}