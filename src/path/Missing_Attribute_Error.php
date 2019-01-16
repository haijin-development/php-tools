<?php

namespace Haijin;

/**
 * This Path_Error is raised when trying to read or write an inexistent nested attribute from or to an object.
 */
class Missing_Attribute_Error extends Path_Error
{
    protected $missing_attribute_path;
    protected $full_attribute_path;
    protected $object;

    /// Initializing

    /**
     * Initializes the instance.
     *
     * @param object The error message.
     * @param object The object trying to be accessed using an Attribute_Path.
     * @param Attribute_Path $full_attribute_path The complete Attribute_Path that was intended to be accessed.
     * @param Attribute_Path $missing_attribute_path The nested path that is missing from the $object.
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
     * Returns the object trying to be accessed using an Attribute_Path.
     *
     * @return Attribute_Path The object trying to be accessed using the complete Attribute_Path.
     */
    public function get_object()
    {
        return $this->object;
    }

    /**
     * Returns the complete Attribute_Path that was intended to be accessed from an object.
     *
     * @return Attribute_Path The complete Attribute_Path that was intended to be accessed.
     */
    public function get_full_attribute_path()
    {
        return $this->full_attribute_path;
    }

    /**
     * Returns the path that is missing from the $object.
     *
     * @return Attribute_Path The nested path that is missing from the $object.
     */
    public function get_missing_attribute_path()
    {
        return $this->missing_attribute_path;
    }
}