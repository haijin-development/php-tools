# Dictionary

An alternative to using PHP arrays for associative collections.

It is always passed by reference and has a consistent, simple and complete protocol.

## Table of contents

1. [Class methods](#c-1)
    1. [Creating instances](#c-1-1)

2. [Instance methods](#c-2)
    1. [Asking](#c-2-1)
    2. [Accessing associations](#c-2-2)
    3. [Removing associations](#c-2-3)
    4. [Querying](#c-2-4)
    5. [Iterating](#c-2-5)
    6. [Converting](#c-2-6)

<a name="c-1"></a>
## Class methods

<a name="c-1-1"></a>
### Creating instances

```php
/**
 * Creates and returns a new Dictionary with an association in it.
 *
 * Example
 *
 *      $dictionary = Dictionary::with( 'a', 123 );
 *
 * @param object $key A key in the dictionary.
 * @param object $value A value associated to the key.
 *
 * @return Dictionary The created Dictionary with the association in it.
 */
public function with($key, $value);
```


```php
/**
 * Creates and returns a new Dictionary with all the associations in it.
 *
 * Example
 *
 *      $dictionary = Dictionary::withAll( [ 'a' => 1, 'b' => 2, 'c' => 3 ] );
 *
 * @param array|Dictionary $associations The associations to add to the created dictionary.
 *
 * @return Dictionary The Dictionary with the associations in it.
 */
public function withAll($associations);
```

<a name="c-2"></a>
## Instance methods

```php
/**
 * Initializes $this object with an optional array of associations in it.
 *
 * @param array|Dictionary $items Optional - An array or Dictionary with associations to add to $this Dictionary.
 */
public function __construct($associations = []);
```

<a name="c-2-1"></a>
### Asking

```php
/**
 * Returns true if $this Dictionary is empty, false otherwise.
 *
 *  Example
 *
 *      $dictionary->isEmpty(); // => true or false
 *
 * @return bool Returns true if $this dictionary is empty, false otherwise.
 */
public function isEmpty();
```


```php
/**
 * Returns true if $this dictionary is not empty, false otherwise.
 *
 *  Example
 *
 *      $dictionary->notEmpty(); // => true or false
 *
 * @return bool Returns true if $this dictionary is not empty, false otherwise.
 */
public function notEmpty();
```


```php
/**
 * Returns true if $this dictionary has a key defined, false if not.
 *
 * @return bool Returns true if $this dictionary has a key defined, false if not.
 */
public function hasKey($key);
```



```php
/**
 * Returns true if $this dictionary has not a key defined, false if not.
 *
 * @return bool Returns true if $this dictionary has not a key defined, false if not.
 */
public function noKey($key);
```


<a name="c-2-2"></a>
### Accessing associations

```php
/**
 * Returns an array with $this Dictionary keys.
 *
 *  Example
 *
 *      $item = $dictionary->getKeys();
 *
 * @return array An array with the keys of this dictionary.
 */
public function getKeys();
```


```php
/**
 * Returns an array with $this Dictionary values.
 *
 *  Example
 *
 *      $item = $dictionary->getValues();
 *
 * @return array An array with the values of this dictionary.
 */
public function getValues();
```


```php
/**
 * Returns the value at the $key.
 *
 *  Example
 *
 *      $item = $dictionary->at( 'a' );
 *
 * @param object $key The key to look for.
 *
 * @return object The value at the given key.
 */
public function at($key);
```

```php
/**
 * Returns the value at the $key.
 *
 *  Example
 *
 *      $item = $dictionary['a'];
 *
 * @param object $key The key to look for.
 *
 * @return object The value at the given key.
 */
public function []($key);
```

```php
/**
 * Returns the value at the $key. If the key not defined, evaluates the $absentCallable.
 *
 *  Example
 *
 *      $value = $dictionary->atIfAbsent( 'a', function(){
 *          return "A default value";
 *      });
 *
 * @param object $key The key to look for.
 * @param callable $absentCallable The callable to evaluate if the key is not defined.
 *
 * @return object The item at the given position or the result of evaluating the $absentCallable.
 */
public function atIfAbsent($key, $absentCallable);
```



```php
/**
 * Puts a value at a key.
 *
 *  Example
 *
 *      $dictionary->atPut( 'key', 123 );
 *
 * @param object $key The key to associate the value with.
 * @param object $value The value to put in the given $key.
 *
 * @return Dictionary Returns $this dictionary.
 */
public function atPut($key, $value);
```


```php
/**
 * Merges the associations of a Dictionary or array into $this Dictionary.
 *
 *  Example
 *
 *      $dictionary->mergeWith( [ 'key', 123 ] );
 *      $dictionary->mergeWith( $anotherDictionary );
 *
 * @param array|Dictionary $dictionary The associations to merge into $this dictionary.
 *
 * @return Dictionary Returns $this dictionary.
 */
public function mergeWith($associations);
```

<a name="c-2-3"></a>
### Removing associations

```php
/**
 * Removes the association at a key.
 *
 *  Example
 *
 *      $value = $orderedCollection->removeAt( 'a' );
 *
 * @param object $key The key of the association to remove.
 *
 * @return object The value of the association removed.
 */
public function removeAt($key);
```



```php
/**
 * Removes the association at a key. If the key is not defined evaluates the
 * absent callable.
 *
 *  Example
 *
 *      $value = $orderedCollection->removeAt( 'a', function() {
 *          return "absent value";
 *      });
 *
 * @param object $key The key of the association to remove.
 * @param callable $absentCallable A callable to evaluate of the key to remove is not defined.
 */
public function removeAtIfAbsent($key, $absentCallable);
```

<a name="c-2-4"></a>
### Querying

```php
/**
 * Returns the number of associations $this dictionary has.
 *
 * @return int The number of associations $this dictionary has.
 */
public function size();
```

<a name="c-2-5"></a>
### Iterating

```php
/**
 * Evaluates a callable on each (key, value) association in $this dictionary.
 * Returns $this object.
 *
 *  Example
 *
 *      $dictionary->keysAndValuesDo( function($key, $value) {
 *          print $key;
 *          print $value;
 *      });
 *
 * @param callable $callable A callable that is evaluated on each key and value pair in the dictionary.
 *
 * @return Dictionary Returns $this dictionary.
 */
public function keysAndValuesDo($callable);
```


```php
/**
 * Evaluates a callable on each key in $this dictionary.
 * Returns $this object.
 *
 *  Example
 *
 *      $dictionary->keysDo( function($key) {
 *          print $key;
 *      });
 *
 * @param callable $callable A callable that is evaluated on each key in $this dictionary.
 *
 * @return Dictionary Returns $this dictionary.
 */
public function keysDo($callable);
```


```php
/**
 * Evaluates a callable on each value in $this dictionary.
 * Returns $this object.
 *
 *  Example
 *
 *      $dictionary->valuesDo( function($value) {
 *          print $value;
 *      });
 *
 * @param callable $callable A callable that is evaluated on each value in $this dictionary.
 *
 * @return Dictionary Returns $this dictionary.
 */
public function valuesDo($callable);
```

<a name="c-2-6"></a>
### Converting

```php
/**
 * Returns an array with the items in $this Dictionary.
 * This is not a getter on the internal array. A copy is returned.
 *
 * @return array Returns an array with the items in $this Dictionary.
 */
public function toArray();
```


```php
/**
 * Returns a string representation of $this collection.
 *
 * @return string A string representation of $this collection.
 */
public function toString();
```
