# OrderedCollection

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
 * Creates and returns a new OrderedCollection with an item in it.
 *
 * Example
 *
 *      $orderedCollection = OrderedCollection::with( 123 );
 *
 * @param object $item An item to add to the created collection.
 *
 * @return OrderedCollection The OrderedCollection with the item in it.
 */
public function with($item);
```


```php
/**
 * Creates and returns a new OrderedCollection with all the items in it.
 *
 * Example
 *
 *      $orderedCollection = OrderedCollection::withAll( [ 1, 2, 3 ] );
 *
 * @param array|OrderedCollection $items The items to add to the created collection.
 *
 * @return OrderedCollection The OrderedCollection with the items in it.
 */
public function withAll($items);
```


<a name="c-2" ></a>
## Instance methods

```php
/**
 * Initializes $this object with an optional array of items in it.
 *
 * @param array|OrderedCollection $items Optional - An array or OrderedCollection with items to add to $this OrderedCollection.
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
 *      $orderedCollection->isEmpty(); // => true or false
 *
 * @return bool Returns true if $this collection is empty, false otherwise.
 */
public function isEmpty();
```


```php
/**
 * Returns true if $this collection is not empty, false otherwise.
 *
 *  Example
 *
 *      $orderedCollection->notEmpty(); // => true or false
 *
 * @return bool Returns true if $this collection is not empty, false otherwise.
 */
public function notEmpty();
```

<a name="c-2-2" ></a>
### Adding items

```php
/**
 * Adds an item at the end of $this collection.
 *
 *  Example
 *
 *      $orderedCollection->add( 123 );
 *
 * @param object $item The item to append to the collection.
 *
 * @return OrderedCollection Returns $this collection.
 */
public function add($item);
```


```php
/**
 * Adds a list of items at the end of $this collection.
 *
 *  Example
 *
 *      $orderedCollection->addAll( [ 1, 2, 3 ] );
 *
 * @param array|OrderedCollection $items The items to append to the collection.
 *
 * @return OrderedCollection Returns $this collection.
 */
public function addAll($items);
```


```php
/**
 * Adds an item at an indexed position.
 *
 *  Example
 *
 *      $orderedCollection->addAt( 'new item', 0 );
 *      $orderedCollection->addAt( 'new item', -1 );
 *
 * @param object $item The item to add.
 * @param int $index The position to add the item.
 *
 * @return OrderedCollection Returns $this object.
 */
public function addAt($item, $index);
```


```php
/**
 * Adds a collection of items at an indexed position.
 *
 *  Example
 *
 *      $orderedCollection->addAllAt( [ 'new item', 'another new item' ], 0 );
 *      $orderedCollection->addAllAt( [ 'new item', 'another new item' ], -1 );
 *
 * @param array $items The items to add.
 * @param int $index The position to add the items.
 *
 * @return OrderedCollection Returns $this object.
 */
public function addAllAt($items, $index);
```

<a name="c-2-3" ></a>
### Accessing items

```php
/**
 * Returns the first item.
 *
 *  Example
 *
 *      $item = $orderedCollection->first();
 *
 * @return object The item at the first position.
 */
public function first();
```

```php
/**
 * Returns the last item.
 *
 *  Example
 *
 *      $item = $orderedCollection->last();
 *
 * @return object The item at the last position.
 */
public function last();
```

```php
/**
 * Returns the item at the $index position.
 *
 *  Example
 *
 *      $item = $orderedCollection->at( 0 );
 *      $item = $orderedCollection->at( -1 );
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
 *      $item = $orderedCollection[ 0 ];
 *      $item = $orderedCollection[ -1 ];
 *
 * @param int $index The position to look for.
 *
 * @return object The item at the given position.
 */
public function []($index);
```

```php
/**
 * Returns the item at the $index position. If there is no item at that position, evaluates the $absentCallable.
 *
 *  Example
 *
 *      $item = $orderedCollection->atIfAbsent( 3, function(){
 *          return "A default item";
 *      });
 *
 * @param int $index The position to look for.
 * @param callable $absentCallable The callable to evaluate if there is no item at $index.
 *
 * @return object The item at the given position or the result of evaluating the $absentCallable.
 */
public function atIfAbsent($index, $absentCallable);
```


```php
/**
 * Puts an item at a position.
 *
 *  Example
 *
 *      $orderedCollection->atPut( 0, 'replace item' );
 *      $orderedCollection->atPut( -1, 'replace item' );
 *
 * @param int $index The position to put the element.
 * @param object $item The item to put in the given $index position.
 *
 * @return OrderedCollection Returns $this collection.
 */
public function atPut($index, $item);
```

<a name="c-2-4" ></a>
### Removing items

```php
/**
 * Removes the first item.
 *
 *  Example
 *
 *      $item = $orderedCollection->removeFirst();
 *
 * @return object The last item removed.
 */
public function removeFirst();
```

```php
/**
 * Removes the last item.
 *
 *  Example
 *
 *      $item = $orderedCollection->removeLast();
 *
 * @return object The last item removed.
 */
public function removeLast();
```


```php
/**
 * Removes the item at an indexed position.
 * If the index is out of range raises an Out_Of_Range_Error.
 *
 *  Example
 *
 *      $item = $orderedCollection->removeAt( 0 );
 *
 * @param int $index The index of the item to remove.
 *
 * @return object The item removed.
 */
public function removeAt($index);
```


```php
/**
 * Removes the item at an indexed position. If the index is out of rante evaluates the $absentCallable.
 *
 *  Example
 *
 *      $item = $orderedCollection->removeAt( 0, function() {
 *          return "absent value";
 *      });
 *
 * @param int $index The index of the item to remove.
 * @param callable $absentCallable A callable to evaluate if the $index is out of range.
 *
 * @return object The item removed.
 */
public function removeAtIfAbsent($index, $absentCallable);
```

```php
/**
 * Removes all the ocurrences of an item in the collection.
 *
 *  Example
 *
 *      $item = $orderedCollection->remove( $object );
 *
 * @return object Returns $this collection.
 */
public function remove($item);
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
 * Finds and returns the first item that makes the callable evaluate to true.
 * Raises an error if no item is found.
 *
 *  Example
 *
 *      $firstItemFound = $orderedCollection->findFirst( function($each) {
 *          return $each->isActive();
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item and returns true or false.
 *
 * @return object The first item that makes the callable return true.
 */
public function findFirst($callable);
```


```php
/**
 * Finds and returns the first item that makes the callable evaluate to true. If no item is found
 * evaluates and returns an absentCallable.
 *
 *  Example
 *
 *      $firstItemFound = $orderedCollection->findFirstIfAbsent(
 *          function($each) { return $each->isActive(); },
 *          function() { return "default value"; },
 *          $this
 *      );
 *
 * @param callable $callable A callable that is evaluated on each item and returns true or false.
 * @param callable $absentCallable A callable that is evaluated if no item is found.
 *
 * @return object The first item that makes the callable return true, or the result of evaluating the absentCallable..
 */
public function findFirstIfAbsent($callable, $absentCallable);
```


```php
/**
 * Finds and returns the index of the first item that makes the callable evaluate to true.
 * Returns -1 if no item is found.
 *
 *  Example
 *
 *      $index = $orderedCollection->findFirstIndex( function($each) {
 *          return $each->isActive();
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item and returns true or false.
 *
 * @return int The index of the first item that makes the callable return true, or -1 if no item is found.
 */
public function findFirstIndex($callable);
```


```php
/**
 * Finds and returns the last item that makes the callable evaluate to true.
 * Raises an error if no item is found.
 *
 *  Example
 *
 *      $lastItemFound = $orderedCollection->findLast( function($each) {
 *          return $each->isActive();
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item and returns true or false.
 *
 * @return object The last item that makes the callable return true.
 */
public function findLast($callable);
```


```php
/**
 * Finds and returns the last item that makes the callable evaluate to true. If none is found evaluates
 * and returns the absentCallable.
 *
 *  Example
 *
 *      $lastItemFound = $orderedCollection->findLastIfAbsent(
 *              function($each) { return $each->isActive(); },
 *              function() { return "default value"; },
 *              $this
 *          );
 *
 * @param callable $callable A callable that is evaluated on each item and returns true or false.
 * @param callable $absentCallable A callable that is evaluated if no item is found.
 *
 * @return object The last item that makes the callable return true, or the result of evaluating the absentCallable..
 */
public function findLastIfAbsent($callable, $absentCallable);
```


```php
/**
 * Finds and returns the index of the last item that makes the callable evaluate to true.
 * Returns -1 if no item is found.
 *
 *  Example
 *
 *      $index = $orderedCollection->findLastIndex( function($each) {
 *          return $each->isActive();
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item and returns true or false.
 *
 * @return int The index of the last item that makes the callable return true, or -1 if no item is found.
 */
public function findLastIndex($callable);
```


```php
/**
 * Evaluates a callable on each item in the collection.
 * Returns $this object.
 *
 *  Example
 *
 *      $orderedCollection->eachDo( function($each) {
 *          print $each;
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item in the collection.
 *
 * @return OrderedCollection Returns $this collection.
 */
public function eachDo($callable);
```


```php
/**
 * Evaluates a callable on each item and index in the collection.
 * Returns $this object.
 *
 *  Example
 *
 *      $orderedCollection->eachWithIndexDo( function($each, $index) {
 *          print $each;
 *          print $index;
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item in the collection.
 *
 * @return OrderedCollection Returns $this collection.
 */
public function eachWithIndexDo($callable);
```


```php
/**
 * Evaluates a callable on each item in the collection from its end to its beginning.
 * Returns $this object.
 *
 *  Example
 *
 *      $orderedCollection->reverseDo( function($each) {
 *          print $each;
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item in the collection.
 *
 * @return OrderedCollection Returns $this collection.
 */
public function reverseDo($callable);
```


```php
/**
 * Evaluates a callable on each item in the collection and returns a new OrderedCollection with only
 * those items that makes the callable evaluate to true.
 *
 *  Example
 *
 *      $selectedItems = $orderedCollection->select( function($each) {
 *          return $each->isActive();
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item in the collection and returns true or false.
 *
 * @return OrderedCollection A new OrderedCollection with the items that makes the callable evaluate to true.
 */
public function select($callable);
```


```php
/**
 * Evaluates a callable on each item in the collection and returns a new OrderedCollection with only
 * those items that makes the callable evaluate to false.
 *
 *  Example
 *
 *      $selectedItems = $orderedCollection->reject( function($each) {
 *          return $each->notActive();
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item in the collection and returns true or false.
 *
 * @return OrderedCollection A new OrderedCollection with the items that makes the callable evaluate to false.
 */
public function reject($callable);
```


```php
/**
 * Evaluates a callable on each item in the collection and returns a new OrderedCollection with the results
 * collected on each evaluation.
 *
 *  Example
 *
 *      $selectedItems = $orderedCollection->collect( function($n) {
 *          return $n * 2;
 *      });
 *
 * @param callable $callable A callable that is evaluated on each item in the collection and returns something.
 *
 * @return OrderedCollection A new OrderedCollection with the items collected from the callable evaluation on each item.
 */
public function collect($callable);
```


```php
/**
 * Evaluates a callable on an accumulator and on each item in the collection. Returns the value of the accumualted
 * variable.
 *
 *  Example
 *
 *      $sum = $orderedCollection->collect( 0, function($sum, $n) {
 *          return $sum = $sum + $n;
 *      });
 *
 * @param object $acummulator The initial value of the acummulator.
 * @param callable $callable A callable that is evaluated on the accumulator and each item in the collection.
 *
 * @return object The acummulated value.
 */
public function acummulate($acummulator, $callable);
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
