<?php

use Haijin\Errors\OutOfRangeError;
use Haijin\OrderedCollection;

$spec->describe("An OrderedCollection", function () {

    $this->describe("when creating instances", function () {

        $this->it("creates an instance with an item", function () {

            $collection = OrderedCollection::with(1);

            $this->expect($collection->toArray())->to()->equal([1]);

        });

        $this->it("creates an instance with many items", function () {

            $collection = OrderedCollection::withAll([1, 2]);

            $this->expect($collection->toArray())->to()->equal([1, 2]);

        });

        $this->it("creates an instance from another instance", function () {

            $collection = OrderedCollection::withAll(OrderedCollection::withAll([1, 2]));

            $this->expect($collection->toArray())->to()->equal([1, 2]);

        });

    });


    $this->describe("when asking if it is empty", function () {

        $this->it("returns if empty or not", function () {

            $collection = new OrderedCollection();

            $this->expect($collection->isEmpty())->to()->be()->true();

            $collection = OrderedCollection::with(1);

            $this->expect($collection->isEmpty())->to()->be()->false();

        });

        $this->it("returns if not empty or empty", function () {

            $collection = OrderedCollection::with(1);

            $this->expect($collection->notEmpty())->to()->be()->true();

            $collection = new OrderedCollection();

            $this->expect($collection->notEmpty())->to()->be()->false();

        });

    });

    $this->describe("when adding items", function () {

        $this->it("adds an item at the end of the collection", function () {

            $collection = new OrderedCollection();
            $collection->add(1);

            $this->expect($collection->toArray())->to()->equal([1]);

            $collection = new OrderedCollection();
            $collection[] = 1;

            $this->expect($collection->toArray())->to()->equal([1]);

        });

        $this->it("adds many items at the end of the collection", function () {

            $collection = new OrderedCollection();
            $collection->addAll([1, 2]);

            $this->expect($collection->toArray())->to()->equal([1, 2]);

            $anotherCollection = new OrderedCollection();
            $anotherCollection->addAll([1, 2]);

            $collection = new OrderedCollection();
            $collection->addAll($anotherCollection);

            $this->expect($collection->toArray())->to()->equal([1, 2]);

        });

        $this->it("adds an item at an index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $collection->addAt('a', 1);

            $this->expect($collection->toArray())->to()->equal([1, 'a', 2, 3]);

        });

    });

    $this->describe("when accessing items", function () {

        $this->it("returns the first item", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->expect($collection->first())->to()->equal(1);

        });

        $this->it("returns the last item", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->expect($collection->last())->to()->equal(3);

        });

        $this->it("returns the item at an index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->expect($collection->at(0))->to()->equal(1);
            $this->expect($collection->at(1))->to()->equal(2);
            $this->expect($collection->at(2))->to()->equal(3);

            $this->expect($collection[0])->to()->equal(1);
            $this->expect($collection[1])->to()->equal(2);
            $this->expect($collection[2])->to()->equal(3);

        });

        $this->it("returns the item at a negative index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->expect($collection->at(-1))->to()->equal(3);
            $this->expect($collection->at(-2))->to()->equal(2);
            $this->expect($collection->at(-3))->to()->equal(1);


            $this->expect($collection[-1])->to()->equal(3);
            $this->expect($collection[-2])->to()->equal(2);
            $this->expect($collection[-3])->to()->equal(1);

        });

        $this->it("raises an Out_Of_Range_Error if the index is invalid", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->expect(function () use ($collection) {

                $collection->at(3);

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index 3 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(3);
                });

            $this->expect(function () use ($collection) {

                $collection[3];

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index 3 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(3);

                });

            $this->expect(function () use ($collection) {

                $collection->at(-4);

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index -4 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(-4);

                });

            $this->expect(function () use ($collection) {

                $collection[-4];

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index -4 is out of range.");

                    $this->expect($error->getCollection())->to()
                        ->be("===")->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(-4);
                });

        });

        $this->it("returns the item at an index or evaluates a callable if the index is invalid", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $item = $collection->atIfAbsent(2, function () {
                return 'not found';
            });
            $this->expect($item)->to()->equal(3);

            $item = $collection->atIfAbsent(3, function () {
                return 'not found';
            });
            $this->expect($item)->to()->equal('not found');

            $item = $collection->atIfAbsent(3, 'not found');
            $this->expect($item)->to()->equal('not found');

        });

        $this->it("returns the item at an index or evaluates a callable if the negative index is invalid", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $item = $collection->atIfAbsent(-1, function () {
                return 'not found';
            });
            $this->expect($item)->to()->equal(3);

            $item = $collection->atIfAbsent(-4, function () {
                return 'not found';
            });
            $this->expect($item)->to()->equal('not found');

        });

    });

    $this->describe("when putting items", function () {

        $this->it("puts an item at an index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection->atPut(1, 'a');
            $this->expect($collection->toArray())->to()->equal([1, 'a', 3]);


            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection[1] = 'a';
            $this->expect($collection->toArray())->to()->equal([1, 'a', 3]);

        });

        $this->it("puts an item at a negative index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection->atPut(-1, 'a');
            $this->expect($collection->toArray())->to()->equal([1, 2, 'a']);


            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection[-1] = 'a';
            $this->expect($collection->toArray())->to()->equal([1, 2, 'a']);

        });

        $this->it("appends an item if the putAt index is the size of the collection", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection->atPut(3, 'a');
            $this->expect($collection->toArray())->to()->equal([1, 2, 3, 'a']);


            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection[3] = 'a';
            $this->expect($collection->toArray())->to()->equal([1, 2, 3, 'a']);

        });

        $this->it("raises an Out_Of_Range_Error if the index is invalid", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->expect(function () use ($collection) {

                $collection->atPut(4, 'a');

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index 4 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(4);

                });

            $this->expect(function () use ($collection) {

                $collection[4] = 'a';

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index 4 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(4);

                });

            $this->expect(function () use ($collection) {

                $collection->atPut(-4, 'a');

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index -4 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(-4);

                });

            $this->expect(function () use ($collection) {

                $collection[-4] = 'a';

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index -4 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(-4);

                });

        });

    });

    $this->describe("when removing items", function () {

        $this->it("removes the first item in the collection", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection->removeFirst();

            $this->expect($collection->toArray())->to()->equal([2, 3]);

        });

        $this->it("removes the last item in the collection", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection->removeLast();

            $this->expect($collection->toArray())->to()->equal([1, 2]);

        });

        $this->it("removes the item at a valid index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $item = $collection->removeAt(0);

            $this->expect($item)->to()->equal(1);
            $this->expect($collection->toArray())->to()->equal([2, 3]);


            $collection = OrderedCollection::withAll([1, 2, 3]);
            $item = $collection->removeAt(1);

            $this->expect($item)->to()->equal(2);
            $this->expect($collection->toArray())->to()->equal([1, 3]);

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $item = $collection->removeAt(2);

            $this->expect($item)->to()->equal(3);
            $this->expect($collection->toArray())->to()->equal([1, 2]);


            $collection = OrderedCollection::withAll([1, 2, 3]);
            unset($collection[0]);
            $this->expect($collection->toArray())->to()->equal([2, 3]);

        });

        $this->it("removes the item at a valid negative index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection->removeAt(-1);

            $this->expect($collection->toArray())->to()->equal([1, 2]);

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection->removeAt(-2);

            $this->expect($collection->toArray())->to()->equal([1, 3]);

            $collection = OrderedCollection::withAll([1, 2, 3]);
            $collection->removeAt(-3);

            $this->expect($collection->toArray())->to()->equal([2, 3]);

        });

        $this->it("raises an Out_Of_Range_Error if the index to remove is invalid", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->expect(function () use ($collection) {

                $collection->removeAt(3);

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index 3 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(3);

                });

            $this->expect(function () use ($collection) {

                $collection->removeAt(-4);

            })->to()->raise(
                OutOfRangeError::class,
                function ($error) use ($collection) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The index -4 is out of range.");

                    $this->expect($error->getCollection())->to()->be("===")
                        ->than($collection);

                    $this->expect($error->getIndex())->to()
                        ->equal(-4);
                });

        });

        $this->it("removes the item at a valid index or evaluates the callable at an invalid index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $item = $collection->removeAtIfAbsent(0, function () {
                return "absent index";
            });

            $this->expect($item)->to()->equal(1);
            $this->expect($collection->toArray())->to()->equal([2, 3]);

        });

        $this->it("removes the item at a valid index or evaluates the callable at an invalid index", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $item = $collection->removeAtIfAbsent(3, function () {
                return "absent index";
            });

            $this->expect($item)->to()->equal("absent index");
            $this->expect($collection->toArray())->to()->equal([1, 2, 3]);

            $item = $collection->removeAtIfAbsent(3, "absent index");

            $this->expect($item)->to()->equal("absent index");
            $this->expect($collection->toArray())->to()->equal([1, 2, 3]);

        });

        $this->it("removes all the ocurrences of an item in the collection", function () {

            $collection = OrderedCollection::withAll(['a', 'b', 'a', 'c', 'a']);
            $collection->remove('a');

            $this->expect($collection->toArray())->to()->equal(['b', 'c']);

        });

        $this->it("does not fail when removing an absent item from the collection", function () {

            $collection = OrderedCollection::withAll(['a', 'b', 'c']);
            $collection->remove('d');

            $this->expect($collection->toArray())->to()->equal(['a', 'b', 'c']);

        });

    });

    $this->describe("when searching for items", function () {

        $this->describe("with findFirst()", function () {

            $this->it("finds the first matching item", function () {

                $collection = OrderedCollection::withAll([1, 2, 3]);

                $item = $collection->findFirst(function ($each) {
                    return $each % 2 == 1;
                });

                $this->expect($item)->to()->equal(1);

            });

            $this->it("finds the index of the first matching item", function () {

                $collection = OrderedCollection::withAll([1, 2, 3]);

                $item = $collection->findFirstIndex(function ($each) {
                    return $each % 2 == 1;
                });

                $this->expect($item)->to()->equal(0);

            });

        });

        $this->describe("with findFirstIfAbsent()", function () {

            $this->it("finds the last matching item with an absent value", function () {

                $collection = OrderedCollection::withAll([1, 2, 3]);

                $item = $collection->findFirstIfAbsent(function ($each) {
                    return $each % 2 == 1;
                }, 'absent value');

                $this->expect($item)->to()->equal(1);

            });

            $this->it("returns the absent value when finding the last matching item with an absent value", function () {

                $collection = new OrderedCollection();

                $item = $collection->findFirstIfAbsent(function ($each) {
                    return $each % 2 == 1;
                }, 'absent value');

                $this->expect($item)->to()->equal('absent value');

            });

            $this->it("returns the absent callable when finding the last matching item with an absent value", function () {

                $collection = new OrderedCollection();

                $item = $collection->findFirstIfAbsent(function ($each) {
                    return $each % 2 == 1;
                }, function () {
                    return 'absent value';
                });

                $this->expect($item)->to()->equal('absent value');

            });

        });

        $this->describe("with findLast()", function () {

            $this->it("finds the last matching item", function () {

                $collection = OrderedCollection::withAll([1, 2, 3]);

                $item = $collection->findLast(function ($each) {
                    return $each % 2 == 1;
                });

                $this->expect($item)->to()->equal(3);

            });

        });

        $this->describe("with findLastIndex()", function () {

            $this->it("finds the index of the last matching item", function () {

                $collection = OrderedCollection::withAll([1, 2, 3]);

                $item = $collection->findLastIndex(function ($each) {
                    return $each % 2 == 1;
                });

                $this->expect($item)->to()->equal(2);

            });

            $this->it("returns -1 if no match is found", function () {

                $collection = new OrderedCollection();

                $item = $collection->findLastIndex(function ($each) {
                    return $each % 2 == 1;
                });

                $this->expect($item)->to()->equal(-1);

            });

        });

        $this->describe("with findLastIfAbsent()", function () {

            $this->it("finds the last matching item with an absent value", function () {

                $collection = OrderedCollection::withAll([1, 2, 3]);

                $item = $collection->findLastIfAbsent(function ($each) {
                    return $each % 2 == 1;
                }, 'absent value');

                $this->expect($item)->to()->equal(3);

            });

            $this->it("returns the absent value when finding the last matching item with an absent value", function () {

                $collection = new OrderedCollection();

                $item = $collection->findLastIfAbsent(function ($each) {
                    return $each % 2 == 1;
                }, 'absent value');

                $this->expect($item)->to()->equal('absent value');

            });

            $this->it("returns the absent callable when finding the last matching item with an absent value", function () {

                $collection = new OrderedCollection();

                $item = $collection->findLastIfAbsent(function ($each) {
                    return $each % 2 == 1;
                }, function () {
                    return 'absent value';
                });

                $this->expect($item)->to()->equal('absent value');

            });

        });

    });

    $this->describe("when iterating items", function () {

        $this->it("iterates over all the items in the collection", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->items = [];

            $collection->eachDo(function ($each) {
                $this->items[] = $each;
            });

            $this->expect($this->items)->to()->equal([1, 2, 3]);

        });

        $this->it("iterates over all the indices and items in the collection", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->items = [];
            $this->indices = [];

            $collection->eachWithIndexDo(function ($each, $i) {
                $this->items[] = $each;
                $this->indices[] = $i;
            });

            $this->expect($this->items)->to()->equal([1, 2, 3]);
            $this->expect($this->indices)->to()->equal([0, 1, 2]);

        });

        $this->it("iterates all the items in the collection backwards", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $this->items = [];

            $collection->reverseDo(function ($each) {
                $this->items[] = $each;
            });

            $this->expect($this->items)->to()->equal([3, 2, 1]);

        });

        $this->it("returns a new OrderedCollection with only the items that matches a filter", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $selectedValues = $collection->select(function ($each) {
                return $each % 2 == 1;
            });

            $this->expect($selectedValues->toArray())->to()->equal([1, 3]);

        });

        $this->it("returns a new OrderedCollection applying a callable to each item in the collection", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $collectedValues = $collection->collect(function ($each) {
                return $each * 2;
            });

            $this->expect($collectedValues->toArray())->to()->equal([2, 4, 6]);

        });

        $this->it("accumulates a value into a variable", function () {

            $collection = OrderedCollection::withAll([1, 2, 3]);

            $sum = $collection->acummulate(10, function ($sum, $each) {
                return $sum = $sum + $each;
            });

            $this->expect($sum)->to()->equal(16);

        });

    });

    $this->describe("when querying for items", function () {

        $this->it("returns true if includes an item using == as comparison", function () {

            $collection = OrderedCollection::withAll(["a", "b", "c"]);

            $this->expect($collection->includes("a"))->to()->be()->true();
            $this->expect($collection->includes("d"))->to()->be()->false();

        });

        $this->it("returns true if does not include an item using == as comparison", function () {

            $collection = OrderedCollection::withAll(["a", "b", "c"]);

            $this->expect($collection->includesNot("d"))->be()->true();
            $this->expect($collection->includesNot("a"))->be()->false();

        });

    });

    $this->describe("when joining string items", function () {

        $this->it("joins the string items of a collection with a given separator string", function () {

            $collection = OrderedCollection::withAll(["a", "b", "c"]);

            $this->expect($collection->joinWith('.'))->to()->equal("a.b.c");

        });

    });

    $this->describe("when cloning the instance", function () {

        $this->it("creates a copy of the collection", function () {

            $collection_1 = OrderedCollection::with(1);

            $collection_2 = clone $collection_1;

            $collection_1->add(2);

            $this->expect($collection_1->size())->to()->equal(2);
            $this->expect($collection_2->size())->to()->equal(1);

        });

    });

    $this->describe("when using the ArrayAccess protocol", function () {

        $this->it("gets a value", function () {

            $collection = new OrderedCollection(['a']);

            $this->expect($collection[0])->to()->equal('a');

        });

        $this->it("sets a value", function () {

            $collection = new OrderedCollection();

            $collection[0] = 'a';

            $this->expect($collection[0])->to()->equal('a');

        });

        $this->it("unsets a value", function () {

            $collection = new OrderedCollection();

            $collection[0] = 'a';

            unset($collection[0]);

            $this->expect(isset($collection[0]))->to()->be()->false();

        });

        $this->it("returns if isset", function () {

            $collection = new OrderedCollection();

            $collection[0] = 'a';

            $this->expect(isset($collection[0]))->to()->be()->true();
            $this->expect(isset($collection[1]))->to()->be()->false();

        });

    });

});