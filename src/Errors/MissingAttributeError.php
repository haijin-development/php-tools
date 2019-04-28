<?php

namespace Haijin\Errors;

/**
 * This Path_Error is raised when trying to read or write an inexistent nested attribute from or to an object.
 */
class MissingAttributeError extends HaijinError
{
    protected $missingAttributePath;
    protected $fullAttributePath;
    protected $object;

    /// Initializing

    /**
     * Initializes the instance.
     *
     * @param object The error message.
     * @param object The object trying to be accessed using an AttributePath.
     * @param AttributePath $fullAttributePath The complete AttributePath that was intended to be accessed.
     * @param AttributePath $missingAttributePath The nested path that is missing from the $object.
     */
    public function __construct($message, $object, $fullAttributePath, $missingAttributePath)
    {
        parent::__construct($message);

        $this->object = $object;
        $this->fullAttributePath = $fullAttributePath;
        $this->missingAttributePath = $missingAttributePath;
    }

    /// Accessing

    /**
     * Returns the object trying to be accessed using an AttributePath.
     *
     * @return AttributePath The object trying to be accessed using the complete AttributePath.
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Returns the complete AttributePath that was intended to be accessed from an object.
     *
     * @return AttributePath The complete AttributePath that was intended to be accessed.
     */
    public function getFullAttributePath()
    {
        return $this->fullAttributePath;
    }

    /**
     * Returns the path that is missing from the $object.
     *
     * @return AttributePath The nested path that is missing from the $object.
     */
    public function getMissingAttributePath()
    {
        return $this->missingAttributePath;
    }
}