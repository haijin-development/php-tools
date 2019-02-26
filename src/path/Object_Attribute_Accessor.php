<?php

namespace Haijin;

/**
 * A wrapper on an object to access its nested attributes with a single message.
 *
 * Examples:
 *
 *      use Haijin\Object_Attribute_Accessor;
 *
 *      /// Reads an attribute from an associative array
 *      
 *      $user = [
 *          'name' => 'Lisa',
 *          'last_name' => 'Simpson',
 *          'address' => [
 *              'street' => 'Evergreen 742'
 *          ]
 *      ];
 *      
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->get_value_at( 'address.street' );
 *      
 *      print( $value . "\n" );
 *      
 *      /// Reads an attribute and if it is missing evaluates a closure
 *      
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->get_value_at_if_absent( "address.number",  function() { return "Absent value"; })
 *
 *      print( $value . "\n" );
 *
 *      /// Reads an attribute and if it is missing returns a constant
 *      
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->get_value_at_if_absent( "address.number",  "Absent value" )
 *
 *      print( $value . "\n" );
 *
 *      /// Writes an attribute to an associative array
 *      
 *      $user = [
 *          'name' => 'Lisa',
 *          'last_name' => 'Simpson',
 *          'address' => [
 *              'street' => 'Evergreen 742'
 *          ]
 *      ];
 *
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->set_value_at( 'address.street', 123 );
 *      
 *      var_dump( $user );
 *      
 *      /// Writes an attribute to an associative array creating the missing attributes
 *
 *      $user = [
 *          'name' => 'Lisa',
 *          'last_name' => 'Simpson',
 *      ];
 *
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->create_value_at( 'address.street', 123 );
 *
 *      var_dump( $user );
 *
 *      /// Reads an attribute from an indexed array
 *      
 *      $user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];
 *      
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->get_value_at( '[1].[0]' );
 *
 *      print( $value . "\n" );
 *      
 *      /// Writes an attribute to an indexed array
 *      
 *      $user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];
 *      
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->set_value_at( '[1].[0]', 123 );
 *
 *      var_dump( $user );
 *      
 *      /// Reads an attribute from an object
 *      
 *      $user = new stdclass();
 *      $user->name = 'Lisa';
 *      $user->last_name = 'Simpson';
 *      $user->address = new stdclass();
 *      $user->address->street = 'Evergreen 742';
 *      
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->get_value_at( 'address.street' );
 *      
 *      print( $value . "\n" );
 *      
 *      /// Writes an attribute to an object
 *
 *      $user = new stdclass();
 *      $user->name = 'Lisa';
 *      $user->last_name = 'Simpson';
 *      $user->address = new stdclass();
 *      $user->address->street = 'Evergreen 742';
 *      
 *      $accessor = new Object_Attribute_Accessor( $user );
 *      $value = $accessor->set_value_at( 'address.street', 123 );
 *      
 *      var_dump( $user );
 */
class Object_Attribute_Accessor
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
     * Returns true if the object has the attribute defined, false otherwise.
     *
     * @param Attribute_Path $attribute_path The attribute to read from $this->object.
     *
     * @return boolean Returns true if the object has the attribute defined, false otherwise.
     */
    public function is_defined($attribute_path)
    {
        if( ! is_a( $attribute_path, "Haijin\Attribute_Path" ) ) {
            $attribute_path = new Attribute_Path( $attribute_path );
        }

        $current_value =& $this->object;

        foreach( $attribute_path->to_array() as $attribute ) {

            $key = $this->get_key_from( $attribute );

            if( ! array_key_exists( $key, $current_value ) )
                return false;

            if( is_array( $current_value ) )
                $current_value =& $current_value[ $key ];
            else
                $current_value =& $current_value->$key;
        }

        return true;
    }

    /**
     * Returns true if the object has not the attribute defined, false if it is defined.
     *
     * @param Attribute_Path $attribute_path The attribute to read from $this->object.
     *
     * @return boolean Returns true if the object has not the attribute defined, false if it is defined.
     */
    public function not_defined($attribute_path)
    {
        return ! $this->is_defined( $attribute_path );
    }

    /// Accessing

    /**
     * Reads the value of the object attribute.
     *
     * @param Attribute_Path $attribute_path The attribute to read from $this->object.
     *
     * @return object The value of the attribute obtained from the object.
     */
    public function get_value_at($attribute_path)
    {
        return $this->get_value_at_if_absent( $attribute_path, function($missing_path) use($attribute_path) {
            $this->raise_missing_attribute_path_error( $missing_path, $attribute_path );
        });
    }

    public function get_value_at_if_absent($attribute_path, $absent_value, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        if( ! is_a( $attribute_path, "Haijin\Attribute_Path" ) ) {
            $attribute_path = new Attribute_Path( $attribute_path );
        }

        $current_value =& $this->object;
        $partial_path = new Attribute_Path();

        foreach( $attribute_path->to_array() as $attribute ) {
            $partial_path->append( $attribute );

            $key = $this->get_key_from( $attribute );

            if( $current_value === null || ! array_key_exists( $key, $current_value ) ) {
                return ( $absent_value instanceof \Closure ) ?
                    $absent_value->call( $binding, $partial_path ) : $absent_value;
            }

            if( is_array( $current_value ) )
                $current_value =& $current_value[ $key ];
            else
                $current_value =& $current_value->$key;
        }

        if( $current_value === null ) {
            return ( $absent_value instanceof \Closure ) ?
                $absent_value->call( $binding, $partial_path ) : $absent_value;
        }

        return $current_value;
    }

    /**
     * Writes the value to the object attribute. Raises a Missing_Attribute_Error if the attribute path does not exist.
     *
     * @param Attribute_Path $attribute_path The attribute to read from $this->object.
     * @param object $value The value to be written to the object.
     *
     * @return Object_Attribute_Accessor $this object.
     */
    public function set_value_at($attribute_path, $value)
    {
        if( ! is_a( $attribute_path, "Haijin\Attribute_Path" ) ) {
            $attribute_path = new Attribute_Path( $attribute_path );
        }

        $current_value =& $this->object;

        $partial_path = new Attribute_Path();

        foreach( $attribute_path->back()->to_array() as $attribute ) {
            $partial_path->append( $attribute );

            $key = $this->get_key_from( $attribute );

            $this->validate_attribute_existence($current_value, $key, $partial_path, $attribute_path);

            if( is_array( $current_value ) )
                $current_value =& $current_value[ $key ];
            else
                $current_value =& $current_value->$key;
        }

        $last_attribute = $attribute_path->get_last_attribute();

        $partial_path->append( $last_attribute );

        $key = $this->get_key_from( $last_attribute );

        $this->validate_attribute_existence($current_value, $key, $partial_path, $attribute_path);

        if( is_array( $current_value ) )
            $current_value[ $key ] = $value;
        else
            $current_value->$key = $value;

        return $this;
    }

    /**
     * Writes the value to the object attribute. If the attribute path does not exists it creates it.
     *
     * @param Attribute_Path $attribute_path The attribute to read from $this->object.
     * @param object $value The value to be written to the object.
     *
     * @return Object_Attribute_Accessor $this object.
     */
    public function create_value_at($attribute_path, $value)
    {
        if( ! is_a( $attribute_path, "Haijin\Attribute_Path" ) ) {
            $attribute_path = new Attribute_Path( $attribute_path );
        }

        $current_value =& $this->object;

        $partial_path = new Attribute_Path();

        foreach( $attribute_path->back()->to_array() as $attribute ) {
            $partial_path->append( $attribute );

            $key = $this->get_key_from( $attribute );

            if( ! array_key_exists( $key, $current_value ) ) {
                $current_value[ $key ] = [];
            }

            if( is_array( $current_value ) )
                $current_value =& $current_value[ $key ];
            else
                $current_value =& $current_value->$key;
        }

        $last_attribute = $attribute_path->get_last_attribute();

        $partial_path->append( $last_attribute );

        $key = $this->get_key_from( $last_attribute );

        if( is_array( $current_value ) )
            $current_value[ $key ] = $value;
        else
            $current_value->$key = $value;

        return $this;
    }

    protected function get_key_from($attribute)
    {
        if( $attribute[0] == '[' ) {
            return (int) substr( $attribute, 1, -1 );
        }
        else {
            return $attribute;
        }
    }

    /// Errors

    protected function validate_attribute_existence($partial_object, $key, $partial_path, $full_path)
    {
        if( array_key_exists( $key, $partial_object ) )
            return;

        $this->raise_missing_attribute_path_error( $partial_path, $full_path );
    }

    /**
     * Raises a Missing_Attribute_Error error.
     *
     * @param object $object The object from which the path is followed to read the value.
     * @param Attribute_Path The path that is missing from the $object.
     */
    protected function raise_missing_attribute_path_error($missing_path, $full_path)
    {
        throw $this->new_missing_attribute_path_error($this->object, $full_path, $missing_path);
    }

    /**
     * Raises a Missing_Attribute_Error error.
     *
     * @param object $object The object from which the path is followed to read the value.
     * @param Attribute_Path $full_path The complete Attribute_Path that was intended to be accessed.
     * @param Attribute_Path The path that is missing from the $object.
     */
    protected function new_missing_attribute_path_error($object, $full_path, $missing_path)
    {
        return new Missing_Attribute_Error(
                "The nested attribute \"" . $missing_path->to_string() . "\" was not found.",
                $object,
                $full_path,
                $missing_path
            );        
    }
}