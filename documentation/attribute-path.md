# Path classes

A set of classes to model paths of things. Currently there is just an `AttributePath` class.

## Table of contents

1. [AttributePath](#c-1)
2. [Examples](#c-2)
    1. [Creating paths](#c-2-1)
    2. [Concatenating paths](#c-2-2)
    3. [Moving the path back](#c-2-3)
    4. [Appending paths](#c-2-4)
    5. [Dropping path tails](#c-2-5)
    6. [Accessing a nested attribute value](#c-2-6)
3. [FilePath](#c-3)
    1. [Creating paths](#c-3-1)
    2. [Concatenating paths](#c-3-2)
    3. [Moving the path back](#c-3-3)
    4. [Appending paths](#c-3-4)
    5. [Dropping path tails](#c-3-5)
    6. [Absolute paths](#c-3-6)
    7. [FilePath file operations](#c-3-7)



<a name="c-1"></a>
## AttributePath

An attribute path is a sequence of attributes from a root object to a nested attribute of that object.

For instance in the following object

```php
$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
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
use Haijin\AttributePath;

// Creates an empty path.
$path = new AttributePath();

// Creates a path from an attributes chain string.
$path = new AttributePath( 'user.address.street' );

// Creates a path from an attributes chain array.
$path = new AttributePath( ['user', 'address', 'street'] );

// Creates a path from another path.
$path = new AttributePath( new AttributePath( 'user.address.street' ) );
```

<a name="c-2-2"></a>
### Concatenating paths

```php
use Haijin\AttributePath;

// Concatenates two paths into a new one.
$path = new AttributePath( 'user' );
$newPath = $path->concat( new AttributePath( 'address.street' ) );

// Concatenates a string into a new AttributePath.
$path = new AttributePath( 'user' );
$newPath = $path->concat( 'address.street' );

// Concatenates an array of attributes into a new AttributePath.
$path = new AttributePath( 'user' );
$newPath = $path->concat( ['address.street'] );
```

<a name="c-2-3"></a>
### Moving the path back

```php
use Haijin\AttributePath;

// Removes the last attribute from the path into a new path.
$path = new AttributePath( 'user.address.street' );
$newPath = $path->back();

// Removes the last n attributes from the path into a new path.
$path = new AttributePath( 'user.address.street' );
$newPath = $path->back( 2 );
```

<a name="c-2-4"></a>
### Appending paths

```php
use Haijin\AttributePath;

// Appends another Path to a path.
$path = new AttributePath( 'user' );
$path->append( new AttributePath( 'address.street' ) );

// Appends an attributes string to a path.
$path = new AttributePath( 'user' );
$path->append( 'address.street' );

// Appends an attributes array to a path.
$path = new AttributePath( 'user' );
$path->append( ['address.street'] );
```

<a name="c-2-5"></a>
### Dropping path tails

```php
use Haijin\AttributePath;

// Drops the last attribute from the path.
$path = new AttributePath( 'user.address.street' );
$path->drop();

// Drops the last n attributes from the path.
$path = new AttributePath( 'user.address.street' );
$path->drop( 2 );
```

<a name="c-2-6"></a>
### Accessing a nested attribute value

```php
// Reads an attribute from an associative array
$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];
$path = new AttributePath( 'address.street' );
$value = $path->getValueFrom( $user );

// Writes an attribute to an associative array
$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen 742'
    ]
];
$path = new AttributePath( 'address.street' );
$value = $path->setValueTo( $user, 123 );

// Reads an attribute from an indexed array
$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$path = new AttributePath( '[1].[0]' );
$value = $path->getValueFrom( $user );

// Writes an attribute to an indexed array
$user = [ ['Lisa', 'Simpson'], [ 'Evergreen', '742' ] ];

$path = new AttributePath( '[1].[0]' );
$value = $path->setValueTo( $user, 123 );
```

<a name="c-3"></a>
## FilePath

A path to a file or directory.

<a name="c-2"></a>
## Examples

[Code examples](./file-path-examples.php).

<a name="c-2-1"></a>
### Creating paths

```php
use Haijin\FilePath;

// Creates an empty path.
$path = new FilePath();

// Creates a path from an attributes chain string.
$path = new FilePath( 'user/address/street' );

// Creates a path from an attributes chain array.
$path = new FilePath( ['user', 'address', 'street'] );

// Creates a path from another path.
$path = new FilePath( new FilePath( 'user/address/street' ) );
```

<a name="c-2-2"></a>
### Concatenating paths

```php
use Haijin\FilePath;

// Concatenates two paths into a new one.
$path = new FilePath( 'user' );
$newPath = $path->concat( new FilePath( 'address/street' ) );

// Concatenates a string into a new FilePath.
$path = new FilePath( 'user' );
$newPath = $path->concat( 'address/street' );

// Concatenates an array of attributes into a new FilePath.
$path = new FilePath( 'user' );
$newPath = $path->concat( ['address/street'] );
```

<a name="c-2-3"></a>
### Moving the path back

```php
use Haijin\FilePath;

// Removes the last attribute from the path into a new path.
$path = new FilePath( 'user/address/street' );
$newPath = $path->back();

// Removes the last n attributes from the path into a new path.
$path = new FilePath( 'user/address/street' );
$newPath = $path->back( 2 );
```

<a name="c-2-4"></a>
### Appending paths

```php
use Haijin\FilePath;

// Appends another Path to a path.
$path = new FilePath( 'user' );
$path->append( new FilePath( 'address/street' ) );

// Appends a path string to a path.
$path = new FilePath( 'user' );
$path->append( 'address/street' );

// Appends a path array to a path.
$path = new FilePath( 'user' );
$path->append( ['address/street'] );
```

<a name="c-2-5"></a>
### Dropping path tails

```php
use Haijin\FilePath;

// Drops the last part from the path.
$path = new FilePath( 'user/address/street' );
$path->drop();

// Drops the last n parts from the path.
$path = new FilePath( 'user/address/street' );
$path->drop( 2 );
```

<a name="c-2-6"></a>
### Absolute paths

Creates an absolute path.

```php
$path = new FilePath( '/user/address/street' );

$path->isAbsolute();
$path->isRelative();
```

Makes a path absolute or relative

```php
$path = new FilePath( 'user/address/street' );

$path->beAbsolute();
$path->beAbsolute( false );

$path->beRelative();
$path->beRelative( false );
```

<a name="c-2-7"></a>
### FilePath file operations

```php
public function existsFile();

public function existsDirectory();

/**
 * Reads and returns the contents of the file at $this FilePath.
 *
 * @return string  The contents of the file at $this FilePath.
 */
public function readFileContents();

/**
 * Writes the contents to the file at $this FilePath.
 *
 * @param string  The contents to write to the file at $this FilePath.
 */
public function writeFileContents($contents);

/**
 * Recursively creates a subdirectory tree from $this FilePath.
 */
public function createDirectoryPath($permissions = 0777);

/**
 * Deletes the file or the directory with all of its contents.
 */
public function delete();

/**
 * Deletes the file.
 */
public function deleteFile();

/**
 * Recursively deletes the directory and its contents.
 */
public function deleteDirectory();

/**
 * Returns the contents of the directory with the given pattern.
 * If no pattern is given returns all the files and directorys that are direct
 * children from $this directory.
 */
public function getDirectoryContents($searchPattern = "*");
```