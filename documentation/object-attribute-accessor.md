# Object_Attribute_Accessor

A class to dynamically read and write attributes of objects, arrays and associative arrays using a polimprophic interface.

## Table of contents

1. [Examples](#c-1)
    1. [Reads an attribute from an associative array](#c-1-1)
    2. [Reads an attribute and if it is missing evaluates a closure](#c-1-2)
    3. [Reads an attribute and if it is missing returns a constant](#c-1-3)
    4. [Writes an attribute to an associative array](#c-1-4)
    5. [Writes an attribute to an associative array creating the missing attributes](#c-1-5)
    6. [Reads an attribute from an indexed array](#c-1-6)
    7. [Writes an attribute to an indexed array](#c-1-7)
    8. [Writes an attribute to an associative array creating the missing attributes](#c-1-8)
    9. [Reads an attribute from an object](#c-1-9)
    10. [Writes an attribute to an object](#c-1-10)

<a name="c-1"></a>
## Examples

[Code examples](./object-attribute-accessor-examples.php).


<a name="c-1-1"></a>
### Reads an attribute from an associative array

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];

$accessor = new Object_Attribute_Accessor( $user );
$value = $accessor->get_value_at( 'address.street' );
```

<a name="c-1-2"></a>
### Reads an attribute and if it is missing evaluates a closure

```php
$accessor = new Object_Attribute_Accessor( $user );
$value = $accessor->get_value_at_if_absent( "address.number",  function() { return "Absent value"; });
```

<a name="c-1-3"></a>
### Reads an attribute and if it is missing returns a constant

```php
$accessor = new Object_Attribute_Accessor( $user );
$value = $accessor->get_value_at_if_absent( "address.number",  "Absent value" );
```

<a name="c-1-4"></a>
### Writes an attribute to an associative array

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];

$accessor = new Object_Attribute_Accessor( $user );
$accessor->set_value_at( 'address.street', 123 );
```

<a name="c-1-5"></a>
#### Writes an attribute to an associative array creating the missing attributes

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
];

$accessor = new Object_Attribute_Accessor( $user );
$accessor->create_value_at( 'address.street', 123 );
```

<a name="c-1-6"></a>
### Reads an attribute from an indexed array

```php
$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$accessor = new Object_Attribute_Accessor( $user );
$value = $accessor->get_value_at( '[1].[0]' );
```

<a name="c-1-7"></a>
### Writes an attribute to an indexed array

```php
$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$accessor = new Object_Attribute_Accessor( $user );
$accessor->set_value_at( '[1].[0]', 123 );
```

<a name="c-1-8"></a>
#### Writes an attribute to an indexed array creating the missing attributes

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
];

$accessor = new Object_Attribute_Accessor( $user );
$accessor->create_value_at( 'addresses[0].address.street', 123 );
```

<a name="c-1-9"></a>
### Reads an attribute from an object

```php
$user = new stdclass();
$user->name = 'Lisa';
$user->last_name = 'Simpson';
$user->address = new stdclass();
$user->address->street = 'Evergreen 742';

$accessor = new Object_Attribute_Accessor( $user );
$value = $accessor->get_value_at( 'address.street' );
```

<a name="c-1-10"></a>
### Writes an attribute to an object

```php
$user = new stdclass();
$user->name = 'Lisa';
$user->last_name = 'Simpson';
$user->address = new stdclass();
$user->address->street = 'Evergreen 742';

$accessor = new Object_Attribute_Accessor( $user );
$value = $accessor->set_value_at( 'address.street', 123 );
```