<?php

namespace Haijin;

use ArrayAccess;
use Haijin\Errors\OutOfRangeError;


/**
 * An alternative to using PHP arrays for indexed collections.
 * It is always passed by reference and has a consistent, simple and complete protocol.
 */
class OrderedCollection implements ArrayAccess
{
    /// Class methods

    /// Creating

    /**
     * The actual items array.
     */
    protected $items;

    /**
     * Initializes $this object with an optional array of items in it.
     *
     * @param array|OrderedCollection $items Optional - An array or OrderedCollection with items to add to $this OrderedCollection.
     */
    public function __construct($items = [])
    {
        $this->items = [];

        $this->addAll($items);
    }

    /// Instance methods

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
    public function addAll($items)
    {
        if (method_exists($items, 'toArray')) {
            $items = $items->toArray();
        }

        $this->items = array_merge($this->items, $items);
    }

    /**
     * Returns an array with the items in $this collection.
     * This is not a getter on the internal array. A copy is returned.
     *
     * @return array Returns an array with the items in $this collection.
     */
    public function toArray()
    {
        return $this->items;
    }

    /// Asking

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
    static public function with($item)
    {
        return self::withAll([$item]);
    }

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
    static public function withAll($items)
    {
        return new self($items);
    }

    /**
     * Returns true if $this collection is not empty, false otherwise.
     *
     *  Example
     *
     *      $orderedCollection->notEmpty(); // => true or false
     *
     * @return bool Returns true if $this collection is not empty, false otherwise.
     */
    public function notEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * Returns true if $this collection is empty, false otherwise.
     *
     *  Example
     *
     *      $orderedCollection->isEmpty(); // => true or false
     *
     * @return bool Returns true if $this collection is empty, false otherwise.
     */
    public function isEmpty()
    {
        return $this->size() == 0;
    }

    /// Adding

    /**
     * Returns the size of $this collection.
     *
     * @return int The size of $this collection.
     */
    public function size()
    {
        return count($this->items);
    }

    /**
     * Returns true if $this collection includes an object, false otherwise.
     *
     *  Example
     *
     *      $orderedCollection->includes( "value" ); // => true or false
     *
     * @return bool Returns true if $this collection includes an object, false otherwise.
     */
    public function includes($object)
    {
        // Implementation note: optimized
        return in_array($object, $this->items);
    }

    /**
     * Returns true if $this collection does not include an object, false otherwise.
     *
     *  Example
     *
     *      $orderedCollection->includes( "value" ); // => true or false
     *
     * @return bool Returns true if $this collection does not include an object, false otherwise.
     */
    public function includesNot($object)
    {
        // Implementation note: optimized
        return !in_array($object, $this->items);
    }

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
    public function addAt($item, $index)
    {
        return $this->addAllAt([$item], $index);
    }


    /// Accessing items by index

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
    public function addAllAt($items, $index)
    {
        $tail = array_slice($this->items, $index);

        $this->items = array_slice($this->items, 0, $index);
        $this->items = array_merge($this->items, $items);
        $this->items = array_merge($this->items, $tail);

        return $this;
    }

    /**
     * Returns the first item.
     *
     *  Example
     *
     *      $item = $orderedCollection->first();
     *
     * @return object The item at the first position.
     */
    public function first()
    {
        return $this->at(0);
    }

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
    public function at($index)
    {
        return $this->atIfAbsent($index, function ($collection, $index) {
            $this->raiseOutOfRangeError($index);
        });
    }

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
    public function atIfAbsent($index, $absentCallable)
    {
        if (!$this->indexIsInRange($index)) {

            if (is_callable($absentCallable)) {
                return $absentCallable($this, $index);
            } else {
                return $absentCallable;
            }

        }

        if ($index < 0) {
            $index = $this->size() + $index;
        }

        return $this->items[$index];
    }

    /**
     * Returns true if the index is in the collection range of indices, false if not.
     *
     * @return bool Returns true if the index is in the collection range of indices, false if not.
     */
    public function indexIsInRange($index)
    {
        return $index < $this->size() && -$this->size() <= $index;
    }

    /// Removing elements

    /**
     * Raises an Out_Of_Range_Error.
     */
    protected function raiseOutOfRangeError($index)
    {
        throw new OutOfRangeError("The index {$index} is out of range.", $this, $index);
    }

    /**
     * Returns the last item.
     *
     *  Example
     *
     *      $item = $orderedCollection->last();
     *
     * @return object The item at the last position.
     */
    public function last()
    {
        return $this->at(count($this->items) - 1);
    }

    /**
     * Removes the first item.
     *
     *  Example
     *
     *      $item = $orderedCollection->removeFirst();
     *
     * @return object The last item removed.
     */
    public function removeFirst()
    {
        return $this->removeAt(0);
    }

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
    public function removeAt($index)
    {
        return $this->removeAtIfAbsent($index, function ($collection, $index) {
            $this->raiseOutOfRangeError($index);
        });
    }

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
    public function removeAtIfAbsent($index, $absentCallable)
    {
        if (!$this->indexIsInRange($index)) {

            if (is_callable($absentCallable)) {
                return $absentCallable($this, $index);
            } else {
                return $absentCallable;
            }

        }

        if ($index < 0) {
            $index = $this->size() + $index;
        }

        $item = $this->items[$index];

        $this->items = array_merge(
            array_slice($this->items, 0, $index),
            array_slice($this->items, $index + 1)
        );

        return $item;
    }

    /// Querying

    /**
     * Removes the last item.
     *
     *  Example
     *
     *      $item = $orderedCollection->removeLast();
     *
     * @return object The last item removed.
     */
    public function removeLast()
    {
        return $this->removeAt(count($this->items) - 1);
    }

    /// Joining

    /**
     * Removes all the ocurrences of an item in the collection.
     *
     *  Example
     *
     *      $item = $orderedCollection->remove( $object );
     *
     * @return object Returns $this collection.
     */
    public function remove($item)
    {
        // Implementation note: optimized

        $items = [];

        foreach ($this->items as $eachItem) {
            if ($eachItem != $item) {
                $items[] = $eachItem;
            }
        }

        $this->items = $items;

        return $this;
    }

    /// Iterating

    /**
     * Returns the concatenation of all the items in the collection using a $separator
     * string between items.
     *
     * @param string $separator The separator string between two consecutive items.
     *
     * @return string The concatenation of all the items in the collection using the $separator
     *      string between items.
     */
    public function joinWith($separator)
    {
        return join($separator, $this->items);
    }

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
    public function findFirst($callable)
    {
        $index = $this->findFirstIndex($callable);

        return $this->at($index);
    }

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
    public function findFirstIndex($callable)
    {

        foreach ($this->items as $index => $value) {
            if ($callable($value))
                return $index;
        }

        return -1;
    }

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
     * @param callable $absentCallable A callable that is evaluated if no item is found.
     *
     * @return object The first item that makes the callable return true, or the result of evaluating the absentCallable..
     */
    public function findFirstIfAbsent($callable, $absentCallable)
    {
        $index = $this->findFirstIndex($callable);

        if ($index == -1) {
            if (is_callable($absentCallable)) {
                return $absentCallable();
            } else {
                return $absentCallable;
            }
        }

        return $this->at($index);
    }

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
    public function findLast($callable)
    {
        $index = $this->findLastIndex($callable);

        return $this->at($index);
    }

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
    public function findLastIndex($callable)
    {
        for ($i = count($this->items) - 1; $i >= 0; $i--) {
            if ($callable($this->items[$i]))
                return $i;
        }

        return -1;
    }

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
     * @return object The last item that makes the callable return true, or the result of evaluating the absentCallable.
     */
    public function findLastIfAbsent($callable, $absentCallable)
    {
        $index = $this->findLastIndex($callable);

        if ($index == -1) {
            if (is_callable($absentCallable)) {
                return $absentCallable();
            } else {
                return $absentCallable;
            }
        }

        return $this->at($index);
    }

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
    public function eachDo($callable)
    {
        foreach ($this->items as $index => $value) {
            $callable($value);
        }

        return $this;
    }

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
    public function eachWithIndexDo($callable)
    {
        foreach ($this->items as $index => $value) {
            $callable($value, $index);
        }

        return $this;
    }

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
    public function reverseDo($callable)
    {
        for ($i = count($this->items) - 1; $i >= 0; $i--) {
            $callable($this->items[$i]);
        }

        return $this;
    }

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
    public function select($callable)
    {
        $selectedItems = new self();

        foreach ($this->items as $index => $value) {
            if ($callable($value))
                $selectedItems->add($value);
        }

        return $selectedItems;
    }

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
    public function add($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /// Converting

    /**
     * Evaluates a callable on each item in the collection and returns a new OrderedCollection
     * with the results collected on each evaluation.
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
    public function collect($callable)
    {
        $collectedItems = new self();

        foreach ($this->items as $index => $value) {
            $collectedItems->add($callable($value));
        }

        return $collectedItems;
    }

    /// Errors

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
    public function acummulate($acummulator, $callable)
    {
        foreach ($this->items as $index => $value) {
            $acummulator = $callable($acummulator, $value);
        }

        return $acummulator;
    }

    public function offsetExists($offset)
    {
        return $this->indexIsInRange($offset);
    }

    /// ArrayAccess implementation

    public function offsetGet($offset)
    {
        return $this->at($offset);
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $offset = $this->size();
        }

        return $this->atPut($offset, $value);
    }

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
    public function atPut($index, $item)
    {
        if ($index == $this->size()) {
            return $this->add($item);
        }

        if (!$this->indexIsInRange($index)) {
            return $this->raiseOutOfRangeError($index);
        }

        if ($index < 0) {
            $index = $this->size() + $index;
        }

        $this->items[$index] = $item;

        return $this;
    }

    public function offsetUnset($offset)
    {
        return $this->removeAt($offset);
    }
}