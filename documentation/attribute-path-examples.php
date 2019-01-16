<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Haijin\Attribute_Path;

/// Creating paths

// Creates an empty path.
$path = new Attribute_Path();
print( $path . "\n" );

// Creates a path from an attributes chain string.
$path = new Attribute_Path( 'user.address.street' );
print( $path . "\n" );

// Creates a path from an attributes chain array.
$path = new Attribute_Path( ['user', 'address', 'street'] );
print( $path . "\n" );

// Creates a path from another path.
$path = new Attribute_Path( new Attribute_Path( 'user.address.street' ) );
print( $path . "\n" );

/// Concatenating paths

// Concatenates two paths into a new one.
$path = new Attribute_Path( 'user' );
$new_path = $path->concat( new Attribute_Path( 'address.street' ) );
print( $new_path . "\n" );

// Concatenates a string into a new Attribute_Path.
$path = new Attribute_Path( 'user' );
$new_path = $path->concat( 'address.street' );
print( $new_path . "\n" );

// Concatenates an array of attributes into a new Attribute_Path.
$path = new Attribute_Path( 'user' );
$new_path = $path->concat( ['address.street'] );
print( $new_path . "\n" );

/// Moving the path back

// Removes the last attribute from the path into a new path.
$path = new Attribute_Path( 'user.address.street' );
$new_path = $path->back();
print( $new_path . "\n" );

// Removes the last n attributes from the path into a new path.
$path = new Attribute_Path( 'user.address.street' );
$new_path = $path->back( 2 );
print( $new_path . "\n" );

/// Appending paths

// Appends another Path to a path.
$path = new Attribute_Path( 'user' );
$path->append( new Attribute_Path( 'address.street' ) );
print( $path . "\n" );

// Appends an attributes string to a path.
$path = new Attribute_Path( 'user' );
$path->append( 'address.street' );
print( $path . "\n" );

// Appends an attributes array to a path.
$path = new Attribute_Path( 'user' );
$path->append( ['address.street'] );
print( $path . "\n" );

/// Dropping path tails

// Drops the last attribute from the path.
$path = new Attribute_Path( 'user.address.street' );
$path->drop();
print( $path . "\n" );

// Drops the last n attributes from the path.
$path = new Attribute_Path( 'user.address.street' );
$path->drop( 2 );
print( $path . "\n" );

/// Accessing object values

// Reads an attribute from an associative array
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];
$path = new Attribute_Path( 'address.street' );
$value = $path->get_value_from( $user );
print( $value . "\n" );

// Writes an attribute to an associative array
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];
$path = new Attribute_Path( 'address.street' );
$value = $path->set_value_to( $user, 123 );
var_dump( $user );

// Reads an attribute from an indexed array
$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$path = new Attribute_Path( '[1].[0]' );
$value = $path->get_value_from( $user );
print( $value . "\n" );

// Writes an attribute to an indexed array
$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$path = new Attribute_Path( '[1].[0]' );
$value = $path->set_value_to( $user, 123 );
var_dump( $user );
