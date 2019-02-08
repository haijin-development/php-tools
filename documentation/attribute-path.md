# Path classes

A set of classes to model paths of things. Currently there is just an `Attribute_Path` class.

## Table of contents

1. [Attribute_Path](#c-1)
2. [Examples](#c-2)
    1. [Creating paths](#c-2-1)
    2. [Concatenating paths](#c-2-2)
    3. [Moving the path back](#c-2-3)
    4. [Appending paths](#c-2-4)
    5. [Dropping path tails](#c-2-5)
    6. [Accessing a nested attribute value](#c-2-6)
3. [File_Path](#c-3)
    1. [Creating paths](#c-3-1)
    2. [Concatenating paths](#c-3-2)
    3. [Moving the path back](#c-3-3)
    4. [Appending paths](#c-3-4)
    5. [Dropping path tails](#c-3-5)
    6. [Absolute paths](#c-3-6)
    7. [File_Path file operations](#c-3-7)



<a name="c-1"></a>
## Attribute_Path

An attribute path is a sequence of attributes from a root object to a nested attribute of that object.

For instance in the following object

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];
```

to reach the `street` attribute from the root object the accessors path is `address.street`.

Paths may include keyed attributes (like in the example) or indexed attributes if there is an array in the path.

Dealing with attribute paths is common in applications. For instance during the validation of attributes and when converting objects to json or to database records. Libraries sometimes treat paths as trings separated by '.' or '/', or as arrays of components.

This class provides a common interface and a simple way to deal with attribute paths.

<a name="c-2"></a>
## Examples

[Code examples](./attribute-path-examples.php).

<a name="c-2-1"></a>
### Creating paths

```php
use Haijin\Attribute_Path;

// Creates an empty path.
$path = new Attribute_Path();

// Creates a path from an attributes chain string.
$path = new Attribute_Path( 'user.address.street' );

// Creates a path from an attributes chain array.
$path = new Attribute_Path( ['user', 'address', 'street'] );

// Creates a path from another path.
$path = new Attribute_Path( new Attribute_Path( 'user.address.street' ) );
```

<a name="c-2-2"></a>
### Concatenating paths

```php
use Haijin\Attribute_Path;

// Concatenates two paths into a new one.
$path = new Attribute_Path( 'user' );
$new_path = $path->concat( new Attribute_Path( 'address.street' ) );

// Concatenates a string into a new Attribute_Path.
$path = new Attribute_Path( 'user' );
$new_path = $path->concat( 'address.street' );

// Concatenates an array of attributes into a new Attribute_Path.
$path = new Attribute_Path( 'user' );
$new_path = $path->concat( ['address.street'] );
```

<a name="c-2-3"></a>
### Moving the path back

```php
use Haijin\Attribute_Path;

// Removes the last attribute from the path into a new path.
$path = new Attribute_Path( 'user.address.street' );
$new_path = $path->back();

// Removes the last n attributes from the path into a new path.
$path = new Attribute_Path( 'user.address.street' );
$new_path = $path->back( 2 );
```

<a name="c-2-4"></a>
### Appending paths

```php
use Haijin\Attribute_Path;

// Appends another Path to a path.
$path = new Attribute_Path( 'user' );
$path->append( new Attribute_Path( 'address.street' ) );

// Appends an attributes string to a path.
$path = new Attribute_Path( 'user' );
$path->append( 'address.street' );

// Appends an attributes array to a path.
$path = new Attribute_Path( 'user' );
$path->append( ['address.street'] );
```

<a name="c-2-5"></a>
### Dropping path tails

```php
use Haijin\Attribute_Path;

// Drops the last attribute from the path.
$path = new Attribute_Path( 'user.address.street' );
$path->drop();

// Drops the last n attributes from the path.
$path = new Attribute_Path( 'user.address.street' );
$path->drop( 2 );
```

<a name="c-2-6"></a>
### Accessing a nested attribute value

```php
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

// Reads an attribute from an indexed array
$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$path = new Attribute_Path( '[1].[0]' );
$value = $path->get_value_from( $user );

// Writes an attribute to an indexed array
$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$path = new Attribute_Path( '[1].[0]' );
$value = $path->set_value_to( $user, 123 );
```

<a name="c-3"></a>
## File_Path

A path to a file or folder.

<a name="c-2"></a>
## Examples

[Code examples](./file-path-examples.php).

<a name="c-2-1"></a>
### Creating paths

```php
use Haijin\File_Path;

// Creates an empty path.
$path = new File_Path();

// Creates a path from an attributes chain string.
$path = new File_Path( 'user/address/street' );

// Creates a path from an attributes chain array.
$path = new File_Path( ['user', 'address', 'street'] );

// Creates a path from another path.
$path = new File_Path( new File_Path( 'user/address/street' ) );
```

<a name="c-2-2"></a>
### Concatenating paths

```php
use Haijin\File_Path;

// Concatenates two paths into a new one.
$path = new File_Path( 'user' );
$new_path = $path->concat( new File_Path( 'address/street' ) );

// Concatenates a string into a new File_Path.
$path = new File_Path( 'user' );
$new_path = $path->concat( 'address/street' );

// Concatenates an array of attributes into a new File_Path.
$path = new File_Path( 'user' );
$new_path = $path->concat( ['address/street'] );
```

<a name="c-2-3"></a>
### Moving the path back

```php
use Haijin\File_Path;

// Removes the last attribute from the path into a new path.
$path = new File_Path( 'user/address/street' );
$new_path = $path->back();

// Removes the last n attributes from the path into a new path.
$path = new File_Path( 'user/address/street' );
$new_path = $path->back( 2 );
```

<a name="c-2-4"></a>
### Appending paths

```php
use Haijin\File_Path;

// Appends another Path to a path.
$path = new File_Path( 'user' );
$path->append( new File_Path( 'address/street' ) );

// Appends a path string to a path.
$path = new File_Path( 'user' );
$path->append( 'address/street' );

// Appends a path array to a path.
$path = new File_Path( 'user' );
$path->append( ['address/street'] );
```

<a name="c-2-5"></a>
### Dropping path tails

```php
use Haijin\File_Path;

// Drops the last part from the path.
$path = new File_Path( 'user/address/street' );
$path->drop();

// Drops the last n parts from the path.
$path = new File_Path( 'user/address/street' );
$path->drop( 2 );
```

<a name="c-2-6"></a>
### Absolute paths

Creates an absolute path.

```php
$path = new File_Path( '/user/address/street' );

$path->is_absolute();
$path->is_relative();
```

Makes a path absolute or relative

```php
$path = new File_Path( 'user/address/street' );

$path->be_absolute();
$path->be_absolute( false );

$path->be_relative();
$path->be_relative( false );
```

<a name="c-2-7"></a>
### File_Path file operations

```php
public function exists_file();

public function exists_folder();

/**
 * Reads and returns the contents of the file at $this File_Path.
 *
 * @return string  The contents of the file at $this File_Path.
 */
public function file_contents();

/**
 * Writes the contents to the file at $this File_Path.
 *
 * @param string  The contents to write to the file at $this File_Path.
 */
public function write_contents($contents);

/**
 * Recursively creates a subfolder tree from $this File_Path.
 */
public function create_folder_path($permissions = 0777);

/**
 * Deletes the file or the folder with all of its contents.
 */
public function delete();

/**
 * Deletes the file.
 */
public function delete_file();

/**
 * Recursively deletes the folder and its contents.
 */
public function delete_folder();

/**
 * Returns the contents of the folder with the given pattern.
 * If no pattern is given returns all the files and folders that are direct
 * children from $this folder.
 */
public function get_folder_contents($search_pattern = "*");
```