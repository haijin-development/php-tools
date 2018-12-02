# Haijin Tools

Common tools to use in PHP applications.

[![Latest Stable Version](https://poser.pugx.org/haijin/tools/version)](https://packagist.org/packages/haijin/tools)
[![Latest Unstable Version](https://poser.pugx.org/haijin/tools/v/unstable)](https://packagist.org/packages/haijin/tools)
[![Build Status](https://travis-ci.com/haijin-development/php-tools.svg?branch=v0.0.2)](https://travis-ci.com/haijin-development/php-tools)
[![License](https://poser.pugx.org/haijin/tools/license)](https://packagist.org/packages/haijin/tools)

### Version 0.0.3

This library is under active development and no stable version was released yet.

If you like it a lot you may contribute by financing its development. Please contact us by email.

## Table of contents

1. [Installation](#c-1)
2. [Available tools](#c-2)
    1. [AttributePath](#c-2-1)
    2. [FilePath](#c-2-2)
    3. [Object accessor](#c-2-3)
    4. [ClosureContext](#c-2-4)
    5. [OrderedCollection](#c-2-5)
    6. [Dictionary](#c-2-6)
3. [Running the tests](#c-3)

<a name="c-1"></a>
## Installation

Include this library in your project `composer.json` file:

```json
{
    ...

    "require": {
        ...
        "haijin/tools": "^0.0.3",
        ...
    },

    ...
}
```

<a name="c-2"></a>
## Available tools

<a name="c-2-1"></a>
### AttributePath

An AttributePath is a sequence of attributes from a root object to a nested attribute of that object.

```php
$path = new AttributePath( "user.address" );
$path = $path->concat( "street" );

print $path->to_string(); // "user.address.street"
print $path->to_array(); // ["user", "address", "street" ]

$user = [
    "name" => "Lisa",
    "last_name" => "Simpson",
    "address" => [
        "street" => null
    ]
];

$path->set_value_to( $user, "Evergreen 742" );
print $path->get_value_from( $user ); // Evergreen 742

$path = $path->back();
print $path; // "user.address"
```

* [AttributePath protocol](./documentation/attribute-path.md).
* [AttributePath examples](./documentation/attribute-path-examples.php).

<a name="c-2-2"></a>
### FilePath

A path to a file or folder.

```php
$path = new FilePath( "home/dev" );
$path = $path->concat( "src" );

print $path->to_string(); // "home/dev/src"
print $path->to_array(); // ["home", "dev", "src" ]

$path = $path->back();
print $path; // "home/dev"
```

* [FilePath protocol](./documentation/attribute-path.md#c-3).
* [FilePath examples](./documentation/file-path-examples.php).

<a name="c-2-3"></a>
### Object accessor

A class to dynamically read and write objects, arrays and associative arrays attributes using a polimprophic interface.

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'addresses' => [
        [
            'street' => null
        ]
    ]
];

$accessor = new ObjectAttributeAccessor( $user );
$accessor->set_value_at( "addresses.[0].street", "Evergreen 742" );
print $accessor->get_value_at( "addresses.[0].street" ); // Evergreen 742
```

* [ObjectAttributeAccessor protocol](./documentation/object-attribute-accessor.md).
* [ObjectAttributeAccessor examples](./documentation/object-attribute-accessor-examples.php).

<a name="c-2-4"></a>
### ClosureContext

A class to keep and evaluate a closure in the context of an object.

```php
$closure_context = ClosureContext( $object, function() {
    print $this === $object;
    return $this->do_something();
});

$closure_context->evaluate(); // true
```

* [ClosureContext protocol](./documentation/closure-context.md).
* [ClosureContext examples](./documentation/closure-context-examples.php).

<a name="c-2-5"></a>
### OrderedCollection

An alternative to using PHP arrays for indexed collections.

It is always passed by reference and has a consistent, simple and complete protocol.

```php
$ordered_collection = OrderedCollection::with_all( [ 10, 20, 30 ] );
$ordered_collection[] = 40;

print $ordered_collection[0]; // => 10
print $ordered_collection[-1]; // => 40

print $ordered_collection->find_first( function($sum, $each) {
    return $each > 20;
}); // 30

print $ordered_collection->select( function($each) {
    return $each > 20;
}); // [ 30, 40 ]

print $ordered_collection->collect( function($each) {
    return $each + 1;
}); // [ 11, 21, 31, 41 ]


print $ordered_collection->acummulate( 0, function($sum, $each) {
    return $sum = $sum + $each;
}); // 100

$ordered_collection->each_do( function($each) {
    print $each . " ";
}); // 10, 20, 30, 40 

print $ordered_collection->remove_at( 0 ); // 10
```

* [OrderedCollection protocol](./documentation/ordered-collection.md).

<a name="c-2-6"></a>
### Dictionary

An alternative to using PHP arrays for associative collections.

It is always passed by reference and has a consistent, simple and complete protocol.


```php
$dictionary = new Dictionary();
$dictionary['a'] = 10;
$dictionary['b'] = 20;

print $dictionary->get_keys(); // => [ 'a', 'b' ]
print $dictionary->get_values(); // => [ 10, 20 ]

print $dictionary['a']; // => 10

print $dictionary->at_if_absent( 'c', function() {
    return 0;
}); // 0

print $dictionary->at_if_absent( 'c', 0 ); // 0

$dictionary->keys_and_values_do( function($key, $value) {
    print $key . " => " . $value . ", ";
}); // 'a' => 10, 'b' => 20,  

print $dictionary->remove_at( 'a' ); // 10
```

* [Dictionary protocol](./documentation/dictionary.md).


<a name="c-3"></a>
## Running the tests

```
composer test
```