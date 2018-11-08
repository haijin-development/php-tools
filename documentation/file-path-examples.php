<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Haijin\Tools\FilePath;

/// Creating paths

// Creates an empty path.
$path = new FilePath();
print( $path . "\n" );

// Creates a path from an attributes chain string.
$path = new FilePath( 'user/address/street' );
print( $path . "\n" );

// Creates a path from an attributes chain array.
$path = new FilePath( ['user', 'address', 'street'] );
print( $path . "\n" );

// Creates a path from another path.
$path = new FilePath( new FilePath( 'user/address/street' ) );
print( $path . "\n" );

/// Concatenating paths

// Concatenates two paths into a new one.
$path = new FilePath( 'user' );
$new_path = $path->concat( new FilePath( 'address/street' ) );
print( $new_path . "\n" );

// Concatenates a string into a new FilePath.
$path = new FilePath( 'user' );
$new_path = $path->concat( 'address/street' );
print( $new_path . "\n" );

// Concatenates an array of attributes into a new FilePath.
$path = new FilePath( 'user' );
$new_path = $path->concat( ['address/street'] );
print( $new_path . "\n" );

/// Moving the path back

// Removes the last attribute from the path into a new path.
$path = new FilePath( 'user/address/street' );
$new_path = $path->back();
print( $new_path . "\n" );

// Removes the last n attributes from the path into a new path.
$path = new FilePath( 'user/address/street' );
$new_path = $path->back( 2 );
print( $new_path . "\n" );

/// Appending paths

// Appends another Path to a path.
$path = new FilePath( 'user' );
$path->append( new FilePath( 'address/street' ) );
print( $path . "\n" );

// Appends an attributes string to a path.
$path = new FilePath( 'user' );
$path->append( 'address/street' );
print( $path . "\n" );

// Appends an attributes array to a path.
$path = new FilePath( 'user' );
$path->append( ['address/street'] );
print( $path . "\n" );

/// Dropping path tails

// Drops the last attribute from the path.
$path = new FilePath( 'user/address/street' );
$path->drop();
print( $path . "\n" );

// Drops the last n attributes from the path.
$path = new FilePath( 'user/address/street' );
$path->drop( 2 );
print( $path . "\n" );


/// Absolute paths

// Creates an absolute path.
$path = new FilePath( '/user/address/street' );
$path->is_absolute();
$path->is_relative();
print( $path . "\n" );

// Makes a path absolute or relative
// Creates an absolute path.
$path = new FilePath( 'user/address/street' );
$path->be_absolute();
$path->be_absolute( false );

$path->be_relative();
$path->be_relative( false );