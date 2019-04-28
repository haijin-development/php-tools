<?php

namespace Haijin;

use Haijin\Errors\MissingAttributeError;
use stdclass;


/**
 * A wrapper on an object to access its nested attributes with a single message.
 *
 * Examples:
 *
 *      use Haijin\ObjectAttributeAccessor;
 *
 *      /// Reads an attribute from an associative array
 *
 *      $user = [
 *          'name' => 'Lisa',
 *          'lastName' => 'Simpson',
 *          'address' => [
 *              'street' => 'Evergreen 742'
 *          ]
 *      ];
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->getValueAt( 'address.street' );
 *
 *      print( $value . "\n" );
 *
 *      /// Reads an attribute and if it is missing evaluates a callable
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->getValueAtIfAbsent( "address.number",  function() { return "Absent value"; })
 *
 *      print( $value . "\n" );
 *
 *      /// Reads an attribute and if it is missing returns a constant
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->getValueAtIfAbsent( "address.number",  "Absent value" )
 *
 *      print( $value . "\n" );
 *
 *      /// Writes an attribute to an associative array
 *
 *      $user = [
 *          'name' => 'Lisa',
 *          'lastName' => 'Simpson',
 *          'address' => [
 *              'street' => 'Evergreen 742'
 *          ]
 *      ];
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->setValueAt( 'address.street', 123 );
 *
 *      var_dump( $user );
 *
 *      /// Writes an attribute to an associative array creating the missing attributes
 *
 *      $user = [
 *          'name' => 'Lisa',
 *          'lastName' => 'Simpson',
 *      ];
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->createValueAt( 'address.street', 123 );
 *
 *      var_dump( $user );
 *
 *      /// Reads an attribute from an indexed array
 *
 *      $user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->getValueAt( '[1].[0]' );
 *
 *      print( $value . "\n" );
 *
 *      /// Writes an attribute to an indexed array
 *
 *      $user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->setValueAt( '[1].[0]', 123 );
 *
 *      var_dump( $user );
 *
 *      /// Reads an attribute from an object
 *
 *      $user = new stdclass();
 *      $user->name = 'Lisa';
 *      $user->lastName = 'Simpson';
 *      $user->address = new stdclass();
 *      $user->address->street = 'Evergreen 742';
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->getValueAt( 'address.street' );
 *
 *      print( $value . "\n" );
 *
 *      /// Writes an attribute to an object
 *
 *      $user = new stdclass();
 *      $user->name = 'Lisa';
 *      $user->lastName = 'Simpson';
 *      $user->address = new stdclass();
 *      $user->address->street = 'Evergreen 742';
 *
 *      $accessor = new ObjectAttributeAccessor( $user );
 *      $value = $accessor->setValueAt( 'address.street', 123 );
 *
 *      var_dump( $user );
 */
class ObjectAttributeAccessor
{
    /**
     * The object which attributes are to be read and written.
     */
    protected $object;

    /// Initializing

    /**
     * Initializes the instance with the given $object.
     */
    public function __construct(&$object)
    {
        $this->object =& $object;
    }

    /// Asking

    /**
     * Returns true if the object has not the attribute defined, false if it is defined.
     *
     * @param AttributePath $attributePath The attribute to read from $this->object.
     *
     * @return boolean Returns true if the object has not the attribute defined, false if it is defined.
     */
    public function notDefined($attributePath)
    {
        return !$this->isDefined($attributePath);
    }

    /**
     * Returns true if the object has the attribute defined, false otherwise.
     *
     * @param AttributePath $attributePath The attribute to read from $this->object.
     *
     * @return boolean Returns true if the object has the attribute defined, false otherwise.
     */
    public function isDefined($attributePath)
    {
        if (!is_a($attributePath, "Haijin\AttributePath")) {
            $attributePath = new AttributePath($attributePath);
        }

        $currentValue =& $this->object;

        foreach ($attributePath->toArray() as $attribute) {

            $key = $this->getKeyFrom($attribute);

            if (!array_key_exists($key, $currentValue))
                return false;

            if (is_array($currentValue)) {
                $currentValue =& $currentValue[$key];
            } else {
                $currentValue =& $currentValue->$key;
            }
        }

        return true;
    }

    /// Accessing

    protected function getKeyFrom($attribute)
    {
        if ($attribute[0] == '[') {
            return (int)substr($attribute, 1, -1);
        } else {
            return $attribute;
        }
    }

    /**
     * Reads the value of the object attribute.
     *
     * @param AttributePath $attributePath The attribute to read from $this->object.
     *
     * @return object The value of the attribute obtained from the object.
     */
    public function getValueAt($attributePath)
    {
        return $this->getValueAtIfAbsent($attributePath, function ($missingPath) use ($attributePath) {
            $this->raiseMissingAttributePathError($missingPath, $attributePath);
        });
    }

    public function getValueAtIfAbsent($attributePath, $absentValue)
    {
        if (!is_a($attributePath, "Haijin\AttributePath")) {
            $attributePath = new AttributePath($attributePath);
        }

        $currentValue =& $this->object;
        $partialPath = new AttributePath();

        foreach ($attributePath->toArray() as $attribute) {
            $partialPath->append($attribute);

            $key = $this->getKeyFrom($attribute);

            if ($currentValue === null || !array_key_exists($key, $currentValue)) {
                return is_callable($absentValue) ?
                    $absentValue($partialPath) : $absentValue;
            }

            if (is_array($currentValue)) {
                $currentValue =& $currentValue[$key];
            } else {
                $currentValue =& $currentValue->$key;
            }
        }

        if ($currentValue === null) {
            return is_callable($absentValue) ?
                $absentValue($partialPath) : $absentValue;
        }

        return $currentValue;
    }

    /**
     * Raises a Missing_Attribute_Error error.
     *
     * @param object $object The object from which the path is followed to read the value.
     * @param AttributePath The path that is missing from the $object.
     */
    protected function raiseMissingAttributePathError($missingPath, $fullPath)
    {
        throw $this->newMissingAttributePathError($this->object, $fullPath, $missingPath);
    }

    /**
     * Raises a Missing_Attribute_Error error.
     *
     * @param object $object The object from which the path is followed to read the value.
     * @param AttributePath $fullPath The complete AttributePath that was intended to be accessed.
     * @param AttributePath The path that is missing from the $object.
     */
    protected function newMissingAttributePathError($object, $fullPath, $missingPath)
    {
        return new MissingAttributeError(
            "The nested attribute \"" . $missingPath->toString() . "\" was not found.",
            $object,
            $fullPath,
            $missingPath
        );
    }

    /// Errors

    /**
     * Writes the value to the object attribute. Raises a Missing_Attribute_Error if the attribute path does not exist.
     *
     * @param AttributePath $attributePath The attribute to read from $this->object.
     * @param object $value The value to be written to the object.
     *
     * @return ObjectAttributeAccessor $this object.
     */
    public function setValueAt($attributePath, $value)
    {
        if (!is_a($attributePath, "Haijin\AttributePath")) {
            $attributePath = new AttributePath($attributePath);
        }

        $currentValue =& $this->object;

        $partialPath = new AttributePath();

        foreach ($attributePath->back()->toArray() as $attribute) {
            $partialPath->append($attribute);

            $key = $this->getKeyFrom($attribute);

            $this->validateAttributeExistence($currentValue, $key, $partialPath, $attributePath);

            if (is_array($currentValue)) {
                $currentValue =& $currentValue[$key];
            } else {
                $currentValue =& $currentValue->$key;
            }
        }

        $lastAttribute = $attributePath->getLastAttribute();

        $partialPath->append($lastAttribute);

        $key = $this->getKeyFrom($lastAttribute);

        $this->validateAttributeExistence(
            $currentValue,
            $key,
            $partialPath,
            $attributePath
        );

        if (is_array($currentValue)) {
            $currentValue[$key] = $value;
        } else {
            $currentValue->$key = $value;
        }

        return $this->object;
    }

    protected function validateAttributeExistence($partialObject, $key, $partialPath, $fullPath)
    {
        if (array_key_exists($key, $partialObject)) {
            return;
        }

        return $this->raiseMissingAttributePathError($partialPath, $fullPath);
    }

    /**
     * Writes the value to the object attribute. If the attribute path does not exists it creates it.
     *
     * @param AttributePath $attributePath The attribute to read from $this->object.
     * @param object $value The value to be written to the object.
     *
     * @return ObjectAttributeAccessor $this object.
     */
    public function createValueAt($attributePath, $value)
    {
        if (!is_a($attributePath, "Haijin\AttributePath")) {
            $attributePath = new AttributePath($attributePath);
        }

        $createArray = is_array($this->object);

        $currentValue =& $this->object;

        $partialPath = new AttributePath();

        $attributes = $attributePath->toArray();
        $attributesLength = count($attributes);

        for ($i = 0; $i < $attributesLength - 1; $i++) {

            $partialPath->append($attributes[$i]);

            $key = $this->getKeyFrom($attributes[$i]);
            $nextKey = $this->getKeyFrom($attributes[$i + 1]);

            $newObject = $createArray ? [] : new stdclass();

            if (is_array($currentValue)
                &&
                !array_key_exists($key, $currentValue)
            ) {
                if (is_int($nextKey)) {
                    $currentValue[$key] = [];
                } else {
                    $currentValue[$key] = $newObject;
                }
            }

            if (is_object($currentValue)
                &&
                !property_exists($currentValue, $key)
            ) {
                if (is_int($nextKey)) {
                    $currentValue->$key = [];
                } else {
                    $currentValue->$key = $newObject;
                }
            }

            if (is_array($currentValue)) {
                $currentValue =& $currentValue[$key];
            } else {
                $currentValue =& $currentValue->$key;
            }

        }

        $lastAttribute = $attributePath->getLastAttribute();

        $partialPath->append($lastAttribute);

        $key = $this->getKeyFrom($lastAttribute);

        if (is_array($currentValue)) {
            $currentValue[$key] = $value;
        } else {
            $currentValue->$key = $value;
        }

        return $this;
    }
}