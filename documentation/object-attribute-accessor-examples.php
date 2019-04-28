<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Haijin\ObjectAttributeAccessor;

print "\n### Reads an attribute from an associative array\n";

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->getValueAt( 'address.street' );

print( $value . "\n" );

print "\n### Reads an attribute and if it is missing evaluates a callable\n";

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->getValueAtIfAbsent( "address.number",  function() { return "Absent value"; });

print( $value . "\n" );

print "\n### Reads an attribute and if it is missing returns a constant\n";

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->getValueAtIfAbsent( "address.number",  "Absent value" );

print( $value . "\n" );


print "\n### Writes an attribute to an associative array\n";

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->setValueAt( 'address.street', 123 );

var_dump( $user );

print "Writes an attribute to an associative array creating the missing attributes\n";

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
];

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->createValueAt( 'address.street', 123 );

var_dump( $user );

print "\nReads an attribute from an indexed array\n";

$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->getValueAt( '[1].[0]' );

print( $value . "\n" );

print "\n### Writes an attribute to an indexed array\n";

$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->setValueAt( '[1].[0]', 123 );

var_dump( $user );

print "Writes an attribute to an indexed array creating the missing attributes\n";

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
];

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->createValueAt( 'addresses[0].address.street', 123 );

var_dump( $user );

print "\n### Reads an attribute from an object\n";

$user = new stdclass();
$user->name = 'Lisa';
$user->lastName = 'Simpson';
$user->address = new stdclass();
$user->address->street = 'Evergreen 742';

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->getValueAt( 'address.street' );

print( $value . "\n" );

print "\n### Writes an attribute to an object\n";

$user = new stdclass();
$user->name = 'Lisa';
$user->lastName = 'Simpson';
$user->address = new stdclass();
$user->address->street = 'Evergreen 742';

$accessor = new ObjectAttributeAccessor( $user );
$value = $accessor->setValueAt( 'address.street', 123 );

var_dump( $user );