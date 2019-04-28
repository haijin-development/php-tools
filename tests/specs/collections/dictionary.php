<?php

use Haijin\Dictionary;
use Haijin\Errors\MissingKeyError;

$spec->describe("A Dictionary", function () {

    $this->describe("when creating instances", function () {

        $this->it("creates an instance with an association", function () {

            $dictionary = Dictionary::with('a', 1);

            $this->expect($dictionary->toArray())->to()->equal(['a' => 1]);

        });

        $this->it("creates an instance with many associations", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2]);

            $this->expect($dictionary->toArray())->to()->equal(['a' => 1, 'b' => 2]);

        });

        $this->it("creates an instance from another instance", function () {

            $dictionary = Dictionary::withAll(Dictionary::withAll(['a' => 1, 'b' => 2]));

            $this->expect($dictionary->toArray())->to()->equal(['a' => 1, 'b' => 2]);

        });

    });

    $this->describe("when querying for emptyness", function () {

        $this->it("returns true if empty, false otherwise", function () {

            $dictionary = new Dictionary();
            $this->expect($dictionary->isEmpty())->to()->be()->true();

            $dictionary = Dictionary::with('a', 1);
            $this->expect($dictionary->isEmpty())->to()->be()->false();

        });

        $this->it("returns true if not empty, false otherwise", function () {

            $dictionary = Dictionary::with('a', 1);
            $this->expect($dictionary->notEmpty())->to()->be()->true();

            $dictionary = new Dictionary();
            $this->expect($dictionary->notEmpty())->to()->be()->false();

        });

    });

    $this->describe("when asking for key existence", function () {

        $this->it("returns if it has a key", function () {

            $dictionary = new Dictionary(['a' => 1]);

            $this->expect($dictionary->hasKey('a'))->to()->be()->true();
            $this->expect($dictionary->noKey('a'))->to()->be()->false();

        });

        $this->it("returns if it has no key", function () {

            $dictionary = new Dictionary(['b' => 1]);

            $this->expect($dictionary->noKey('a'))->to()->be()->true();
            $this->expect($dictionary->hasKey('a'))->to()->be()->false();

        });

    });

    $this->describe("when accessing items", function () {

        $this->it("returns all the keys", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $this->expect($dictionary->getKeys())->to()->equal(['a', 'b', 'c']);

        });

        $this->it("returns all the values", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $this->expect($dictionary->getValues())->to()->equal([1, 2, 3]);

        });

        $this->it("return the value at a key", function () {

            $dictionary = Dictionary::with('a', 1);

            $this->expect($dictionary->at('a'))->to()->equal(1);
            $this->expect($dictionary['a'])->to()->equal(1);

        });

        $this->it("raises a Missing_Key_Error if the key is missing", function () {

            $dictionary = Dictionary::with('a', 1);

            $this->expect(function () use ($dictionary) {

                $dictionary->at('b');

            })->to()->raise(
                MissingKeyError::class,
                function ($error) use ($dictionary) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The key 'b' is not defined.");

                    $this->expect($error->getDictionary())->to()->be("===")
                        ->than($dictionary);

                    $this->expect($error->getKey())->to()->equal('b');
                }
            );

            $this->expect(function () use ($dictionary) {

                $dictionary['b'];

            })->to()->raise(
                MissingKeyError::class,
                function ($error) use ($dictionary) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The key 'b' is not defined.");

                    $this->expect($error->getDictionary())->to()->be("===")
                        ->than($dictionary);

                    $this->expect($error->getKey())->to()->equal('b');
                }
            );

        });

        $this->it("evaluates a callable if the key is missing, otherwise returns the value at the key", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $value = $dictionary->atIfAbsent('c', function () {
                return 'not found';
            });
            $this->expect($value)->to()->equal(3);

            $value = $dictionary->atIfAbsent('d', function () {
                return 'not found';
            });
            $this->expect($value)->to()->equal('not found');

            $value = $dictionary->atIfAbsent('d', 'not found');
            $this->expect($value)->to()->equal('not found');

        });

    });

    $this->describe("when putting items", function () {

        $this->it("puts a value at a key", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);
            $dictionary->atPut('b', 123);

            $this->expect($dictionary->toArray())->to()
                ->equal(['a' => 1, 'b' => 123, 'c' => 3]);

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);
            $dictionary['b'] = 123;

            $this->expect($dictionary->toArray())->to()
                ->equal(['a' => 1, 'b' => 123, 'c' => 3]);

        });

    });

    $this->describe("when removing associations", function () {

        $this->it("removes the value at a key", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $value = $dictionary->removeAt('b');

            $this->expect($value)->to()->equal(2);
            $this->expect($dictionary->toArray())->to()->equal(['a' => 1, 'c' => 3]);


            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);
            unset($dictionary['b']);

            $this->expect($dictionary->toArray())->to()->equal(['a' => 1, 'c' => 3]);

        });

        $this->it("raises a Missing_Key_Error if the key to remove is missing", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $this->expect(function () use ($dictionary) {

                $dictionary->removeAt('d');

            })->to()->raise(
                MissingKeyError::class,
                function ($error) use ($dictionary) {

                    $this->expect($error->getMessage())->to()
                        ->equal("The key 'd' is not defined.");

                    $this->expect($error->getDictionary())->to()->be("===")
                        ->than($dictionary);

                    $this->expect($error->getKey())->to()
                        ->equal('d');
                });

        });

        $this->it("removes the value at the key", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $value = $dictionary->removeAtIfAbsent('a', function () {
                return "default value";
            });

            $this->expect($value)->to()->equal(1);
            $this->expect($dictionary->toArray())->to()->equal(['b' => 2, 'c' => 3]);

        });

        $this->it("evaluates a callable if the key to remove is missing", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $value = $dictionary->removeAtIfAbsent('d', function () {
                return "default value";
            });

            $this->expect($value)->to()->equal("default value");
            $this->expect($dictionary->toArray())->to()
                ->equal(['a' => 1, 'b' => 2, 'c' => 3]);

            $value = $dictionary->removeAtIfAbsent('d', "default value");

            $this->expect($value)->to()->equal("default value");
            $this->expect($dictionary->toArray())->to()
                ->equal(['a' => 1, 'b' => 2, 'c' => 3]);

        });

    });

    $this->describe("when iterating", function () {

        $this->it("iterates over all its associations of keys and values", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $this->keys = [];
            $this->values = [];

            $dictionary->keysAndValuesDo(function ($key, $value) {
                $this->keys[] = $key;
                $this->values[] = $value;
            });

            $this->expect($this->keys)->to()->equal(['a', 'b', 'c']);
            $this->expect($this->values)->to()->equal([1, 2, 3]);

        });

        $this->it("iterates over all its keys", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $this->keys = [];

            $dictionary->keysDo(function ($key) {
                $this->keys[] = $key;
            });

            $this->expect($this->keys)->to()->equal(['a', 'b', 'c']);

        });

        $this->it("iterates over all its values", function () {

            $dictionary = Dictionary::withAll(['a' => 1, 'b' => 2, 'c' => 3]);

            $this->values = [];

            $dictionary->valuesDo(function ($value) {
                $this->values[] = $value;
            });

            $this->expect($this->values)->to()->equal([1, 2, 3]);

        });

    });

    $this->describe("when cloning the instance", function () {

        $this->it("creates a copy of the dictionary", function () {

            $dictionary_1 = Dictionary::with("a", 1);

            $dictionary_2 = clone $dictionary_1;

            $dictionary_1["b"] = 2;

            $this->expect($dictionary_1->size())->to()->equal(2);
            $this->expect($dictionary_2->size())->to()->equal(1);

        });

    });

    $this->describe("when using the ArrayAccess protocol", function () {

        $this->it("gets a value", function () {

            $dictionary = new Dictionary(['a' => 1]);

            $this->expect($dictionary['a'])->to()->equal(1);

        });

        $this->it("sets a value", function () {

            $dictionary = new Dictionary();

            $dictionary['a'] = 1;

            $this->expect($dictionary['a'])->to()->equal(1);

        });

        $this->it("unsets a value", function () {

            $dictionary = new Dictionary();

            $dictionary['a'] = 1;
            unset($dictionary['a']);

            $this->expect($dictionary->hasKey('a'))->to()->be()->false();

        });

        $this->it("returns if isset", function () {

            $dictionary = new Dictionary();

            $dictionary['a'] = 1;

            $this->expect(isset($dictionary['a']))->to()->be()->true();
            $this->expect(isset($dictionary['b']))->to()->be()->false();

        });

    });

});