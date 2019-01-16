# Ordered_Collection

An alternative to using PHP arrays for indexed collections.

It is always passed by reference and has a consistent, simple and complete protocol.

## Table of contents

1. [Class methods](#c-1)
    1. [Creating instances](#c-1-1)

2. [Instance methods](#c-2)
    1. [Asking](#c-2-1)
    2. [Adding items](#c-2-2)
    3. [Accessing items](#c-2-3)
    4. [Removing items](#c-2-4)
    5. [Querying](#c-2-5)
    6. [Iterating](#c-2-6)
    7. [Converting](#c-2-7)


<a name="c-1" ></a>
## Class methods

<a name="c-1-1" ></a>
### Creating instances
```php
/**
 * Creates and returns a new Ordered_Collection with an item in it.
 *
 * Example
 *
 *      $ordered_collection = Ordered_Collection::with( 123 );
 *
 * @param object $item An item to add to the created collection.
 *
 * @return Ordered_Collection The Ordered_Collection with the item in it.
 */
public function with($item);
```


```php
/**
 * Creates and returns a new Ordered_Collection with all the items in it.
 *
 * Example
 *
 *      $ordered_collection = Ordered_Collection::with_all( [ 1, 2, 3 ] );
 *
 * @param array|Ordered_Collection $items The items to add to the created collection.
 *
 * @return Ordered_Collection The Ordered_Collection with the items in it.
 */
public function with_all($items);
```


<a name="c-2" ></a>
## Instance methods

```php
/**
 * Initializes $this object with an optional array of items in it.
 *
 * @param array|Ordered_Collection $items Optional - An array or Ordered_Collection with items to add to $this Ordered_Collection.
 */
public function __construct($items = []);
```

<a name="c-2-1" ></a>
### Asking

```php
/**
 * Returns true if $this collection is empty, false otherwise.
 *
 *  Example
 *
 *      $ordered_collection->is_empty(); // => true or false
 *
 * @return bool Returns true if $this collection is empty, false otherwise.
 */
public function is_empty();
```


```php
/**
 * Returns true if $this collection is not empty, false otherwise.
 *
 *  Example
 *
 *      $ordered_collection->not_empty(); // => true or false
 *
 * @return bool Returns true if $this collection is not empty, false otherwise.
 */
public function not_empty();
```

<a name="c-2-2" ></a>
### Adding items

```php
/**
 * Adds an item at the end of $this collection.
 *
 *  Example
 *
 *      $ordered_collection->add( 123 );
 *
 * @param object $item The item to append to the collection.
 *
 * @return Ordered_Collection Returns $this collection.
 */
public function add($item);
```


```php
/**
 * Adds a list of items at the end of $this collection.
 *
 *  Example
 *
 *      $ordered_collection->add_all( [ 1, 2, 3 ] );
 *
 * @param array|Ordered_Collection $items The items to append to the collection.
 *
 * @return Ordered_Collection Returns $this collection.
 */
public function add_all($items);
```


```php
/**
 * Adds an item at an indexed position.
 *
 *  Example
 *
 *      $ordered_collection->add_at( 'new item', 0 );
 *      $ordered_collection->add_at( 'new item', -1 );
 *
 * @param object $item The item to add.
 * @param int $index The position to add the item.
 *
 * @return Ordered_Collection Returns $this object.
 */
public function add_at($item, $index);
```


```php
/**
 * Adds a collection of items at an indexed position.
 *
 *  Example
 *
 *      $ordered_collection->add_all_at( [ 'new item', 'another new item' ], 0 );
 *      $ordered_collection->add_all_at( [ 'new item', 'another new item' ], -1 );
 *
 * @param array $items The items to add.
 * @param int $index The position to add the items.
 *
 * @return Ordered_Collection Returns $this object.
 */
public function add_all_at($items, $index);
```

<a name="c-2-3" ></a>
### Accessing items

```php
/**
 * Returns the item at the $index position.
 *
 *  Example
 *
 *      $item = $ordered_collection->at( 0 );
 *      $item = $ordered_collection->at( -1 );
 *
 * @param int $index The position to look for.
 *
 * @return object The item at the given position.
 */
public function at($index);
```

```php
/**
 * Returns the item at the $index position.
 *
 *  Example
 *
 *      $item = $ordered_collection[ 0 ];
 *      $item = $ordered_collection[ -1 ];
 *
 * @param int $index The position to look for.
 *
 * @return object The item at the given position.
 */
public function []($index);
```

```php
/**
 * Returns the item at the $index position. If there is no item at that position, evaluates the $absent_closure.
 *
 *  Example
 *
 *      $item = $ordered_collection->at_if_absent( 3, function(){
 *          return "A default item";
 *      });
 *
 * @param int $index The position to look for.
 * @param closure $absent_closure The closure to evaluate if there is no item at $index.
 * @param object $binding Optional - An optional binding for the evaluation of the $absent_closure.
 *
 * @return object The item at the given position or the result of evaluating the $absent_closure.
 */
public function at_if_absent($index, $absent_closure, $binding = null);
```


```php
/**
 * Puts an item at a position.
 *
 *  Example
 *
 *      $ordered_collection->at_put( 0, 'replace item' );
 *      $ordered_collection->at_put( -1, 'replace item' );
 *
 * @param int $index The position to put the element.
 * @param object $item The item to put in the given $index position.
 *
 * @return Ordered_Collection Returns $this collection.
 */
public function at_put($index, $item);
```

<a name="c-2-4" ></a>
### Removing items

```php
/**
 * Removes the last item.
 *
 *  Example
 *
 *      $item = $ordered_collection->remove_last();
 *
 * @return object The last item removed.
 */
public function remove_last();
```


```php
/**
 * Removes the item at an indexed position.
 * If the index is out of range raises an Out_Of_Range_Error.
 *
 *  Example
 *
 *      $item = $ordered_collection->remove_at( 0 );
 *
 * @param int $index The index of the item to remove.
 *
 * @return object The item removed.
 */
public function remove_at($index);
```


```php
/**
 * Removes the item at an indexed position. If the index is out of rante evaluates the $absent_closure.
 *
 *  Example
 *
 *      $item = $ordered_collection->remove_at( 0, function() {
 *          return "absent value";
 *      });
 *
 * @param int $index The index of the item to remove.
 * @param closure $absent_closure A closure to evaluate if the $index is out of range.
 * @param object $binding An optional binding to evaluate the absent_closure.
 *
 * @return object The item removed.
 */
public function remove_at_if_absent($index, $absent_closure, $binding = null);
```

<a name="c-2-5" ></a>
### Querying

```php
/**
 * Returns the size of $this collection.
 *
 * @return int The size of $this collection.
 */
public function size();
```

<a name="c-2-6" ></a>
### Iterating

```php
/**
 * Finds and returns the first item that makes the closure evaluate to true.
 * Raises an error if no item is found.
 *
 *  Example
 *
 *      $first_item_found = $ordered_collection->find_first( function($each) {
 *          return $each->is_active();
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item and returns true or false.
 * @param object $binding An optional binding for the closure.
 *
 * @return object The first item that makes the closure return true.
 */
public function find_first($closure, $binding = null);
```


```php
/**
 * Finds and returns the first item that makes the closure evaluate to true. If no item is found
 * evaluates and returns an absent_closure.
 *
 *  Example
 *
 *      $first_item_found = $ordered_collection->find_first_if_absent(
 *          function($each) { return $each->is_active(); },
 *          function() { return "default value"; },
 *          $this
 *      );
 *
 * @param callable $closure A closure that is evaluated on each item and returns true or false.
 * @param callable $absent_closure A closure that is evaluated if no item is found.
 * @param object $binding An optional binding for the closures.
 *
 * @return object The first item that makes the closure return true, or the result of evaluating the absent_closure..
 */
public function find_first_if_absent($closure, $absent_closure, $binding = null);
```


```php
/**
 * Finds and returns the index of the first item that makes the closure evaluate to true.
 * Returns -1 if no item is found.
 *
 *  Example
 *
 *      $index = $ordered_collection->find_first_index( function($each) {
 *          return $each->is_active();
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item and returns true or false.
 * @param object $binding An optional binding for the closure.
 *
 * @return int The index of the first item that makes the closure return true, or -1 if no item is found.
 */
public function find_first_index($closure, $binding = null);
```


```php
/**
 * Finds and returns the last item that makes the closure evaluate to true.
 * Raises an error if no item is found.
 *
 *  Example
 *
 *      $last_item_found = $ordered_collection->find_last( function($each) {
 *          return $each->is_active();
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item and returns true or false.
 * @param object $binding An optional binding for the closure.
 *
 * @return object The last item that makes the closure return true.
 */
public function find_last($closure, $binding = null);
```


```php
/**
 * Finds and returns the last item that makes the closure evaluate to true. If none is found evaluates
 * and returns the absent_closure.
 *
 *  Example
 *
 *      $last_item_found = $ordered_collection->find_last_if_absent(
 *              function($each) { return $each->is_active(); },
 *              function() { return "default value"; },
 *              $this
 *          );
 *
 * @param callable $closure A closure that is evaluated on each item and returns true or false.
 * @param callable $absent_closure A closure that is evaluated if no item is found.
 * @param object $binding An optional binding for the closure.
 *
 * @return object The last item that makes the closure return true, or the result of evaluating the absent_closure..
 */
public function find_last_if_absent($closure, $absent_closure, $binding = null);
```


```php
/**
 * Finds and returns the index of the last item that makes the closure evaluate to true.
 * Returns -1 if no item is found.
 *
 *  Example
 *
 *      $index = $ordered_collection->find_last_index( function($each) {
 *          return $each->is_active();
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item and returns true or false.
 * @param object $binding An optional binding for the closure.
 *
 * @return int The index of the last item that makes the closure return true, or -1 if no item is found.
 */
public function find_last_index($closure, $binding = null);
```


```php
/**
 * Evaluates a closure on each item in the collection.
 * Returns $this object.
 *
 *  Example
 *
 *      $ordered_collection->each_do( function($each) {
 *          print $each;
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item in the collection.
 * @param object $binding An optional binding for the closure.
 *
 * @return Ordered_Collection Returns $this collection.
 */
public function each_do($closure, $binding = null);
```


```php
/**
 * Evaluates a closure on each item and index in the collection.
 * Returns $this object.
 *
 *  Example
 *
 *      $ordered_collection->each_with_index_do( function($each, $index) {
 *          print $each;
 *          print $index;
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item in the collection.
 * @param object $binding An optional binding for the closure.
 *
 * @return Ordered_Collection Returns $this collection.
 */
public function each_with_index_do($closure, $binding = null);
```


```php
/**
 * Evaluates a closure on each item in the collection from its end to its beginning.
 * Returns $this object.
 *
 *  Example
 *
 *      $ordered_collection->reverse_do( function($each) {
 *          print $each;
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item in the collection.
 * @param object $binding An optional binding for the closure.
 *
 * @return Ordered_Collection Returns $this collection.
 */
public function reverse_do($closure, $binding = null);
```


```php
/**
 * Evaluates a closure on each item in the collection and returns a new Ordered_Collection with only
 * those items that makes the closure evaluate to true.
 *
 *  Example
 *
 *      $selected_items = $ordered_collection->select( function($each) {
 *          return $each->is_active();
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item in the collection and returns true or false.
 * @param object $binding An optional binding for the closure.
 *
 * @return Ordered_Collection A new Ordered_Collection with the items that makes the closure evaluate to true.
 */
public function select($closure, $binding = null);
```


```php
/**
 * Evaluates a closure on each item in the collection and returns a new Ordered_Collection with only
 * those items that makes the closure evaluate to false.
 *
 *  Example
 *
 *      $selected_items = $ordered_collection->reject( function($each) {
 *          return $each->not_active();
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item in the collection and returns true or false.
 * @param object $binding An optional binding for the closure.
 *
 * @return Ordered_Collection A new Ordered_Collection with the items that makes the closure evaluate to false.
 */
public function reject($closure, $binding = null);
```


```php
/**
 * Evaluates a closure on each item in the collection and returns a new Ordered_Collection with the results
 * collected on each evaluation.
 *
 *  Example
 *
 *      $selected_items = $ordered_collection->collect( function($n) {
 *          return $n * 2;
 *      }, $this);
 *
 * @param callable $closure A closure that is evaluated on each item in the collection and returns something.
 * @param object $binding An optional binding for the closure.
 *
 * @return Ordered_Collection A new Ordered_Collection with the items collected from the closure evaluation on each item.
 */
public function collect($closure, $binding = null);
```


```php
/**
 * Evaluates a closure on an accumulator and on each item in the collection. Returns the value of the accumualted
 * variable.
 *
 *  Example
 *
 *      $sum = $ordered_collection->collect( 0, function($sum, $n) {
 *          return $sum = $sum + $n;
 *      }, $this);
 *
 * @param object $acummulator The initial value of the acummulator.
 * @param callable $closure A closure that is evaluated on the accumulator and each item in the collection.
 * @param object $binding An optional binding for the closure.
 *
 * @return object The acummulated value.
 */
public function acummulate($acummulator, $closure, $binding = null);
```

<a name="c-2-7" ></a>
### Converting

```php
/**
 * Returns an array with the items in $this collection.
 * This is not a getter on the internal array. A copy is returned.
 *
 * @return array Returns an array with the items in $this collection.
 */
public function to_array();
```


```php
/**
 * Returns a string representation of $this collection.
 *
 * @return string A string representation of $this collection.
 */
public function to_string();
```
