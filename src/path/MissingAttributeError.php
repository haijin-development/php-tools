<?php

namespace Haijin\Tools;

/**
 * This PathError is raised when trying to read or write an inexistent nested attribute from or to an object.
 */
class MissingAttributeError extends PathError
{
    protected $missing_attribute_path;
    protected $full_attribute_path;
    protected $object;

    /// Initializing

    /**
     * Initializes the instance.
     *
     * @param object The error message.
     * @param object The object trying to be accessed using an AttributePath.
     * @param AttributePath $full_attribute_path The complete AttributePath that was intended to be accessed.
     * @param AttributePath $missing_attribute_path The nested path that is missing from the $object.
     */
    public function __construct($message, $object, $full_attribute_path, $missing_attribute_path)
    {
        parent::__construct($message);

        $this->object = $object;
        $this->full_attribute_path = $full_attribute_path;
        $this->missing_attribute_path = $missing_attribute_path;
    }

    /// Accessing

    /**
     * Returns the object trying to be accessed using an AttributePath.
     *
     * @return AttributePath The object trying to be accessed using the complete AttributePath.
     */
    public function get_object()
    {
        return $this->object;
    }

    /**
     * Returns the complete AttributePath that was intended to be accessed from an object.
     *
     * @return AttributePath The complete AttributePath that was intended to be accessed.
     */
    public function get_full_attribute_path()
    {
        return $this->full_attribute_path;
    }

    /**
     * Returns the path that is missing from the $object.
     *
     * @return AttributePath The nested path that is missing from the $object.
     */
    public function get_missing_attribute_path()
    {
        return $this->missing_attribute_path;
    }
}