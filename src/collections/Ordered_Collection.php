<?php

namespace Haijin;

/**
 * An alternative to using PHP arrays for indexed collections.
 * It is always passed by reference and has a consistent, simple and complete protocol.
 */
class Ordered_Collection implements \ArrayAccess
{
    /// Class methods

        /// Creating

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
    static public function with($item)
    {
        return self::with_all( [ $item ] );
    }

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
    static public function with_all($items)
    {
        return new self( $items );
    }

    /// Instance methods

    /**
     * The actual items array.
     */
    protected $items;

    /**
     * Initializes $this object with an optional array of items in it.
     *
     * @param array|Ordered_Collection $items Optional - An array or Ordered_Collection with items to add to $this Ordered_Collection.
     */
    public function __construct($items = [])
    {
        $this->items = [];

        $this->add_all( $items );
    }

    /// Asking

    /**
     * Returns true if $this collection is empty, false otherwise.
     *
     *  Example
     *
     *      $ordered_collection->is_empty(); // => true or false
     *
     * @return bool Returns true if $this collection is empty, false otherwise.
     */
    public function is_empty()
    {
        return $this->size() == 0;
    }

    /**
     * Returns true if $this collection is not empty, false otherwise.
     *
     *  Example
     *
     *      $ordered_collection->not_empty(); // => true or false
     *
     * @return bool Returns true if $this collection is not empty, false otherwise.
     */
    public function not_empty()
    {
        return ! $this->is_empty();
    }

    /**
     * Returns true if $this collection includes an object, false otherwise.
     *
     *  Example
     *
     *      $ordered_collection->includes( "value" ); // => true or false
     *
     * @return bool Returns true if $this collection includes an object, false otherwise.
     */
    public function includes($object)
    {
        // Implementation note: optimized
        return in_array( $object, $this->items );
    }

    /**
     * Returns true if $this collection does not include an object, false otherwise.
     *
     *  Example
     *
     *      $ordered_collection->includes( "value" ); // => true or false
     *
     * @return bool Returns true if $this collection does not include an object, false otherwise.
     */
    public function includes_not($object)
    {
        // Implementation note: optimized
        return ! in_array( $object, $this->items );
    }

    /// Adding

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
    public function add($item)
    {
        $this->items[] = $item;

        return $this;
    }

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
    public function add_all($items)
    {
        if( method_exists( $items, 'to_array' ) ) {
            $items = $items->to_array();
        }

        $this->items = array_merge( $this->items, $items );
    }

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
    public function add_at($item, $index)
    {
        return $this->add_all_at( [ $item ], $index );
    }

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
    public function add_all_at($items, $index)
    {
        $tail = array_slice( $this->items, $index );

        $this->items = array_slice( $this->items, 0, $index );
        $this->items = array_merge( $this->items, $items );
        $this->items = array_merge( $this->items, $tail );

        return $this;
    }


    /// Accessing items by index

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
    public function at($index)
    {
        return $this->at_if_absent( $index, function($collection, $index) {
            $this->raise_out_of_range_error($index);
        });
    }

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
    public function at_if_absent($index, $absent_closure, $binding = null)
    {
        if( ! $this->index_is_in_range($index) ) {
            if( $binding === null ){
                $binding = $this;
            }

            if( $absent_closure instanceof \Closure ) {
                return $absent_closure->call( $binding, $this, $index );
            } else {
                return $absent_closure;
            }
        }

        if( $index < 0 ) {
            $index = $this->size() + $index;
        }

        return $this->items[ $index ];
    }


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
    public function at_put($index, $item)
    {
        if( $index == $this->size() ) {
            return $this->add( $item );
        }

        if( ! $this->index_is_in_range($index) ) {
            return $this->raise_out_of_range_error($index);
        }

        if( $index < 0 ) {
            $index = $this->size() + $index;
        }

        $this->items[ $index ] = $item;

        return $this;
    }

    /// Removing elements

    /**
     * Removes the last item.
     *
     *  Example
     *
     *      $item = $ordered_collection->remove_last();
     *
     * @return object The last item removed.
     */
    public function remove_last()
    {
        // Implementation note: optimized implementation

        if( $this->is_empty() ) {
            return;
        }

        $item = $this->items[ count( $this->items ) - 1 ];

        unset( $this->items[ count( $this->items ) - 1 ] );

        return $item;
    }

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
    public function remove_at($index)
    {
        return $this->remove_at_if_absent( $index, function($collection, $index) {
            $this->raise_out_of_range_error( $index );
        });
    }

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
    public function remove_at_if_absent($index, $absent_closure, $binding = null)
    {
        if( ! $this->index_is_in_range($index) ) {
            if( $binding === null ) {
                $binding = $this;
            }

            if( $absent_closure instanceof \Closure ) {
                return $absent_closure->call( $binding, $this, $index );
            } else {
                return $absent_closure;
            }
        }

        if( $index < 0 ) {
            $index = $this->size() + $index;
        }

        $item = $this->items[ $index ];

        $this->items = array_merge(
            array_slice( $this->items, 0, $index ),
            array_slice( $this->items, $index + 1 )
        );

        return $item;
    }

    /// Querying

    /**
     * Returns the size of $this collection.
     *
     * @return int The size of $this collection.
     */
    public function size()
    {
        return count( $this->items );
    }

    /// Joining

    /**
     * Returns the concatenation of all the items in the collection using a $separator
     * string between items.
     *
     * @param string $separator The separator string between two consecutive items.
     *
     * @return string The concatenation of all the items in the collection using the $separator
     *      string between items.
     */
    public function join_with($separator)
    {
        return join( $separator, $this->items );
    }

    /// Iterating

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
    public function find_first($closure, $binding = null)
    {
        $index = $this->find_first_index( $closure, $binding );

        return $this->at( $index );
    }

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
    public function find_first_if_absent($closure, $absent_closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        $index = $this->find_first_index( $closure, $binding );

        if( $index == -1 ) {
            if( $absent_closure instanceof \Closure ) {
                return $absent_closure->call( $binding );
            } else {
                return $absent_closure;
            }
        }

        return $this->at( $index );
    }

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
    public function find_first_index($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        foreach( $this->items as $index => $value) {
            if( $closure->call( $binding, $value ) )
                return $index;
        }

        return -1;
    }

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
    public function find_last($closure, $binding = null)
    {
        $index = $this->find_last_index( $closure, $binding );

        return $this->at( $index );
    }

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
    public function find_last_if_absent($closure, $absent_closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        $index = $this->find_last_index( $closure, $binding );

        if( $index == -1 ) {
            if( $absent_closure instanceof \Closure ) {
                return $absent_closure->call( $binding );
            } else {
                return $absent_closure;
            }
        }

        return $this->at( $index );
    }

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
    public function find_last_index($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        for( $i = count( $this->items ) - 1; $i >= 0 ; $i-- ) { 
            if( $closure->call( $binding, $this->items[ $i ] ) )
                return $i;            
        }

        return -1;
    }

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
    public function each_do($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        foreach( $this->items as $index => $value ) {
            $closure->call( $binding, $value );
        }

        return $this;
    }

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
    public function each_with_index_do($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        foreach( $this->items as $index => $value ) {
            $closure->call( $binding, $value, $index );
        }

        return $this;
    }

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
    public function reverse_do($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        for( $i = count( $this->items ) - 1; $i >= 0 ; $i-- ) { 
            $closure->call( $binding, $this->items[ $i ] );
        }

        return $this;
    }

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
    public function select($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        $selected_items = new self();

        foreach( $this->items as $index => $value ) {
            if( $closure->call( $binding, $value ) )
                $selected_items->add( $value );
        }

        return $selected_items;
    }

    /**
     * Evaluates a closure on each item in the collection and returns a new Ordered_Collection
     * with the results collected on each evaluation.
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
    public function collect($closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        $collected_items = new self();

        foreach( $this->items as $index => $value ) {
            $collected_items->add( $closure->call( $binding, $value ) );
        }

        return $collected_items;
    }

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
    public function acummulate($acummulator, $closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        foreach( $this->items as $index => $value ) {
            $acummulator = $closure->call( $binding, $acummulator, $value );
        }

        return $acummulator;
    }

    /// Converting

    /**
     * Returns an array with the items in $this collection.
     * This is not a getter on the internal array. A copy is returned.
     *
     * @return array Returns an array with the items in $this collection.
     */
    public function to_array()
    {
        return array_slice( $this->items, 0 );
    }

    /**
     * Returns a string representation of $this collection.
     *
     * @return string A string representation of $this collection.
     */
    public function to_string()
    {
        $s = join( ', ', $this->items );
        return "Ordered_Collection::with_all( [{$s}] )";
    }

    /**
     * Returns a string representation of $this collection.
     *
     * @return string A string representation of $this collection.
     */
    public function __toString()
    {
        return $this->to_string();
    }

    /// Errors

    /**
     * Returns true if the index is in the collection range of indices, false if not.
     *
     * @return bool Returns true if the index is in the collection range of indices, false if not.
     */
    public function index_is_in_range($index)
    {
        return $index < $this->size() && -$this->size() <= $index;
    }

    /**
     * Raises an Out_Of_Range_Error.
     */
    protected function raise_out_of_range_error($index)
    {
        throw new Out_Of_Range_Error( "The index {$index} is out of range.", $this, $index );
    }

    /// ArrayAccess implementation
    
    public function offsetExists( $offset )
    {
        return $this->index_is_in_range( $offset );
    }

    public function offsetGet( $offset )
    {
        return $this->at( $offset );
    }

    public function offsetSet( $offset , $value )
    {
        if( $offset === null ) {
            $offset = $this->size();
        }

        return $this->at_put( $offset, $value );
    }

    public function offsetUnset( $offset )
    {
        return $this->remove_at( $offset );
    }
}