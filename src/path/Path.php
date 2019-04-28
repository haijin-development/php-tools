<?php

namespace Haijin;

use Haijin\Errors\PathError;


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

    /**
     * Flags if $this Path is absolute or relative.
     */
    protected $isAbsolute;

    /// Initializing

    /**
     * Initializes a new Path. Optionaly accepts an $attributesChain.
     *
     * The $attributesChain can be an attributes string (ej. 'address.street'), an attributes
     * array (ej. [ 'address', 'street' ]) or another Path object.
     *
     * @param string|array|Path $attributesChain The attributes to initialize $this object with.
     */
    public function __construct($attributesChain = null, $isAbsolute = false)
    {
        $this->path = [];
        $this->separator = $this->defaultSeparator();
        $this->isAbsolute = $isAbsolute;

        if ($attributesChain !== null)
            $this->path = $this->normalizeAttributesChain($attributesChain);
    }

    /**
     * Returns the string used as a separator between consecutive attributes when converting the Path
     * to a string. For instance '.' for attribute paths or '/' for file paths.
     *
     * @return string The string used as a separator between consecutive attributes when converting the Path
     * to a string.
     */
    public abstract function defaultSeparator();

    /// Constants

    /**
     * Takes a string, array or Path and converts it to an array of single attributes.
     *
     * @param string|array|Path $attributesChain The parameter to normalize.
     *
     * @return array The array of single attributes obtained from normalizing the $attributesChain parameter.
     */
    protected function normalizeAttributesChain($attributesChain)
    {
        if (is_array($attributesChain)) {

            $attributes = $attributesChain;

        } elseif (is_string($attributesChain)) {

            $attributes = explode($this->separator, $attributesChain);

            if (!empty($attributesChain) && $attributes[0] == "") {
                $this->isAbsolute = true;
            }

        } else {

            $attributes = $attributesChain->toArray();
            $this->beAbsolute($attributesChain->isAbsolute());

        }

        $attributes = array_values(array_filter($attributes));

        return $attributes;
    }

    /**
     * Returns the path as an array of single attributes.
     *
     * @return array<string> The array of the attributes in the path.
     */
    public function toArray()
    {
        return $this->path;
    }

    public function beAbsolute($isAbsolute = true)
    {
        $this->isAbsolute = $isAbsolute;

        return $this;
    }

    /// Querying

    /**
     * Returns true if $this FilePath is absolute, false if its relative.
     *
     * @return bool Returns true if $this FilePath is absolute, false if its relative.
     */
    public function isAbsolute()
    {
        return $this->isAbsolute;
    }

    /**
     * Returns true if the path is not empty.
     */
    public function notEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * Returns true if the path is empty.
     */
    public function isEmpty()
    {
        return $this->length() == 0;
    }

    /**
     * Returns the length of $this path.
     *
     * @return integer The length of the path.
     */
    public function length()
    {
        return count($this->path);
    }

    /**
     * Returns false if $this FilePath is relative, false if its absolute.
     *
     * @return bool Returns false if $this FilePath is relative, false if its absolute.
     */
    public function isRelative()
    {
        return !$this->isAbsolute();
    }

    /**
     * Returns the last attribute in the path.
     *
     * @return string The last attribute in the path.
     */
    public function getLastAttribute()
    {
        $count = count($this->path);

        if ($count == 0) {
            return "";
        }

        return $this->path[$count - 1];
    }

    /// Appending and dropping attributes

    /**
     * Concatenates attributes to the path.
     *
     * Returns a new AttributePath with the appended path.
     *
     * @param string|array|Path $attributesChain The attributes to concatenate to $this object.
     *
     * @return A new Path object with the $attributesChain concatenated.
     */
    public function concat($attributesChain)
    {
        $newPath = $this->newInstanceWith($this);

        return $newPath->append($attributesChain);
    }

    /**
     * Creates a new instance of the same class of $this initialized with an $attributesChain.
     *
     * @param string|array|Path $attributesChain The attributes to initialize the new Path object with.
     *
     * @return Path A new Path initialized with the $attributesChain.
     */
    protected function newInstanceWith($attributesChain)
    {
        $class = get_class($this);
        return new $class($attributesChain);
    }

    /**
     * Appends attributes to the path. Modifies the path.
     *
     * Returns $this object to allow chaining calls.
     *
     * @param string|array|Path $attributesChain The attributes to append to $this object.
     *
     * @return Path Returns $this object.
     */
    public function append($attributesChain)
    {
        if (!method_exists($attributesChain, "toArray")) {
            $attributesChain = $this->newInstanceWith($attributesChain);
        }

        $this->path = array_merge($this->path, $attributesChain->toArray());

        return $this;
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
        if ($n < 0) {
            $class = get_class($this);
            return $this->raisePathError("{$class}->back( {$n} ): invalid parameter {$n}.");
        }

        $newPath = $this->newInstanceWith($this);

        return $newPath->drop($n);
    }

    /// Comparing paths

    /**
     * Raises a new Path_Error with a $message.
     *
     * The raising an error is a method on its own to allow subclasses to override it if they want to.
     *
     * @param string $message The error message.
     */
    protected function raisePathError($message)
    {
        throw $this->newPathError($message);
    }

    /**
     * Creates a new Path_Error object with a $message.
     *
     * The creation of an object is a method on its own to allow subclasses to override it with their
     * own Error class if they want to.
     *
     * @param string $message The error message.
     *
     * @return PathError A new ErrorPath object.
     */
    protected function newPathError($message)
    {
        return new PathError($message);
    }

    /// Comparing

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
        if ($n < 0) {

            $class = get_class($this);

            return $this->raisePathError(
                "{$class}->drop( {$n} ): invalid parameter {$n}."
            );

        }

        if ($n > 0) {
            $this->path = array_slice($this->path, 0, -$n);
        }

        return $this;
    }

    /**
     * Returns the common root between $this path and another one.
     */
    public function rootInCommonWith($anotherPath)
    {
        if (is_string($anotherPath) || is_array($anotherPath)) {
            $anotherPath = $this->newInstanceWith($anotherPath);
        }

        $n = min($this->length(), $anotherPath->length());

        $thisArray = $this->toArray();
        $anotherPathArray = $anotherPath->toArray();

        $commonPath = $this->newInstanceWith([]);

        for ($i = 0; $i < $n; $i++) {
            if ($thisArray[$i] != $anotherPathArray[$i]) {
                break;
            }

            $commonPath->append($thisArray[$i]);
        }

        return $commonPath;
    }

    /// Converting

    /**
     * Returns the difference between $this Path and another one.
     */
    public function differenceWith($anotherPath)
    {
        if (is_string($anotherPath) || is_array($anotherPath)) {
            $anotherPath = $this->newInstanceWith($anotherPath);
        }

        $n = $anotherPath->length();

        $anotherPathArray = $anotherPath->toArray();

        $difference = $this->newInstanceWith([]);
        $difference->beRelative();

        $collecting = false;

        foreach ($this->path as $i => $attribute) {

            if (!$collecting
                &&
                ($i >= $n || $attribute != $anotherPathArray[$i])
            ) {
                $collecting = true;
            }

            if ($collecting) {
                $difference->append($attribute);
            }
        }

        return $difference;
    }

    public function beRelative($isRelative = true)
    {
        return $this->beAbsolute(!$isRelative);
    }

    /// Printing

    /**
     * Returns true if $this path is equal to $anotherPath.
     */
    public function equals($anotherPath)
    {
        if (is_string($anotherPath) || is_array($anotherPath)) {
            $anotherPath = $this->newInstanceWith($anotherPath);
        }

        return $this->toArray() == $anotherPath->toArray();
    }

    /// Errors

    /**
     * Returns true if $this path is subpath of $anotherPath.
     */
    public function beginsWith($anotherPath)
    {
        if (is_string($anotherPath) || is_array($anotherPath)) {
            $anotherPath = $this->newInstanceWith($anotherPath);
        }

        $anotherPathLength = $anotherPath->length();

        if ($this->length() < $anotherPathLength) {
            return false;
        }

        $anotherPathArray = $anotherPath->toArray();

        for ($i = 0; $i < $anotherPathLength; $i++) {
            if ($this->path[$i] != $anotherPathArray[$i]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Prints a text representation of $this object.
     *
     * @return string The string representation of $this object.
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Returns the path as a string of attributes separated by dots.
     *
     * @param string $separator Optional - The string used between consecutives attributes. Defaults to ".".
     *
     * @return string The attributes path string.
     */
    public function toString($separator = null)
    {
        if ($separator === null) {
            $separator = $this->separator;
        }

        return join($separator, $this->path);
    }
}