<?php

use Haijin\AttributePath;
use Haijin\Errors\MissingAttributeError;

$spec->describe("When accessing an AttributePath", function () {

    $this->describe("on associative arrays", function () {

        $this->it("reads an attribute value from an associative array", function () {

            $object = [
                'name' => 'Lisa',
                'lastName' => 'Simpson',
                'address' => [
                    'street' => 'Evergreen 742'
                ]
            ];

            $attributePath = new AttributePath('address.street');

            $this->expect($attributePath->getValueFrom($object))->to()
                ->equal('Evergreen 742');

        });

        $this->it("raises an error when reading an inexistent attribute value from an associative array", function () {

            $object = [
                'name' => 'Lisa',
                'lastName' => 'Simpson',
                'address' => [
                    'street' => 'Evergreen 742'
                ]
            ];

            $attributePath = new AttributePath('address.number');

            $this->expect(function () use ($object, $attributePath) {

                $attributePath->getValueFrom($object);

            })->to()->raise(
                MissingAttributeError::class,
                function ($exception) use ($object, $attributePath) {

                    $this->expect($exception->getMessage())->to()
                        ->equal("The nested attribute \"address.number\" was not found.");

                    $this->expect($exception->getObject())->to()->be("===")
                        ->than($object);

                    $this->expect($exception->getFullAttributePath())->to()
                        ->equal($attributePath);

                    $this->expect($exception->getMissingAttributePath()->toString())->to()
                        ->equal('address.number');

                });

        });

        $this->it("writes an attribute value to an associative array", function () {

            $object = [
                'name' => 'Lisa',
                'lastName' => 'Simpson',
                'address' => [
                    'street' => 'Evergreen 742'
                ]
            ];

            $attributePath = new AttributePath('address.street');

            $attributePath->setValueTo($object, 123);

            $this->expect($object['address']['street'])->to()->equal(123);

        });

        $this->it("raises an error when writting an inexistent attribute to an associative array", function () {

            $object = [
                'name' => 'Lisa',
                'lastName' => 'Simpson',
                'address' => [
                    'street' => 'Evergreen 742'
                ]
            ];

            $attributePath = new AttributePath('address.number');

            $this->expect(function () use ($object, $attributePath) {

                $attributePath->setValueTo($object, 123);

            })->to()->raise(
                MissingAttributeError::class,
                function ($exception) use ($object, $attributePath) {

                    $this->expect($exception->getMessage())->to()
                        ->equal("The nested attribute \"address.number\" was not found.");

                    $this->expect($exception->getObject())->to()->be("===")
                        ->than($object);

                    $this->expect($exception->getFullAttributePath())->to()
                        ->equal($attributePath);

                    $this->expect($exception->getMissingAttributePath()->toString())->to()
                        ->equal('address.number');

                });

        });

    });

    $this->describe("on indexed arrays", function () {

        $this->it("reads an attribute value from an indexed array", function () {

            $object = [
                ['Lisa', 'Simpson'],
                ['Evergreen', '742']
            ];

            $attributePath = new AttributePath('[1].[0]');

            $this->expect($attributePath->getValueFrom($object))->to()
                ->equal('Evergreen');

        });

        $this->it("raises an error when reading an inexistent attribute value from an indexed array", function () {

            $object = [
                ['Lisa', 'Simpson'],
                ['Evergreen', '742']
            ];

            $attributePath = new AttributePath('[1].[2]');

            $this->expect(function () use ($object, $attributePath) {

                $attributePath->getValueFrom($object);

            })->to()->raise(
                MissingAttributeError::class,
                function ($exception) use ($object, $attributePath) {
                    $this->expect($exception->getMessage())->to()
                        ->equal("The nested attribute \"[1].[2]\" was not found.");

                    $this->expect($exception->getObject())->to()->be("===")
                        ->than($object);

                    $this->expect($exception->getFullAttributePath())->to()
                        ->equal($attributePath);

                    $this->expect($exception->getMissingAttributePath()->toString())->to()
                        ->equal('[1].[2]');

                });

        });

        $this->it("writes an attribute value to an indexed array", function () {

            $object = [
                ['Lisa', 'Simpson'],
                ['Evergreen', '742']
            ];

            $attributePath = new AttributePath('[1].[0]');

            $attributePath->setValueTo($object, 123);

            $this->expect($object[1][0])->to()->equal(123);

        });

        $this->it("raises an error when writting an inexistent attribute value to an indexed array", function () {

            $object = [
                ['Lisa', 'Simpson'],
                ['Evergreen', '742']
            ];

            $attributePath = new AttributePath('[1].[2]');

            $this->expect(function () use ($object, $attributePath) {

                $attributePath->setValueTo($object, 123);

            })->to()->raise(
                MissingAttributeError::class,
                function ($exception) use ($object, $attributePath) {

                    $this->expect($exception->getMessage())->to()
                        ->equal("The nested attribute \"[1].[2]\" was not found.");

                    $this->expect($exception->getObject())->to()->be("===")
                        ->than($object);

                    $this->expect($exception->getFullAttributePath())->to()
                        ->equal($attributePath);

                    $this->expect($exception->getMissingAttributePath()->toString())->to()
                        ->equal('[1].[2]');

                });

        });

    });

    $this->describe("on objects", function () {

        $this->it("reads an attribute value from an object", function () {

            $object = new stdclass();
            $object->field = new stdclass();
            $object->field->field = 123;

            $attributePath = new AttributePath('field.field');

            $this->expect($attributePath->getValueFrom($object))->to()
                ->equal(123);

        });

        $this->it("raises an error when reading an inexistent property from an object", function () {

            $object = new stdclass();
            $object->field = new stdclass();
            $object->field->field = 123;


            $attributePath = new AttributePath('field.field_2');

            $this->expect(function () use ($object, $attributePath) {

                $attributePath->getValueFrom($object);

            })->to()->raise(
                MissingAttributeError::class,
                function ($exception) use ($object, $attributePath) {

                    $this->expect($exception->getMessage())->to()
                        ->equal("The nested attribute \"field.field_2\" was not found.");

                    $this->expect($exception->getObject())->to()->be("===")
                        ->than($object);

                    $this->expect($exception->getFullAttributePath())->to()
                        ->equal($attributePath);

                    $this->expect($exception->getMissingAttributePath()->toString())->to()
                        ->equal('field.field_2');

                });

        });

        $this->it("testWritesAnAttributeValueToAnObject", function () {

            $object = new stdclass();
            $object->field = new stdclass();
            $object->field->field = 123;

            $attributePath = new AttributePath('field.field');
            $attributePath->setValueTo($object, 111);

            $this->expect($object->field->field)->to()->equal(111);

        });

        $this->it("testRaisesAnErrorWhenWrittingAnInexistentPropertyToAnObject", function () {

            $object = new stdclass();
            $object->field = new stdclass();
            $object->field->field = 123;


            $attributePath = new AttributePath('field.field_2');

            $this->expect(function () use ($object, $attributePath) {

                $attributePath->setValueTo($object, 111);

            })->to()->raise(
                MissingAttributeError::class,
                function ($exception) use ($object, $attributePath) {

                    $this->expect($exception->getMessage())->to()
                        ->equal("The nested attribute \"field.field_2\" was not found.");

                    $this->expect($exception->getObject())->to()->be("===")
                        ->than($object);

                    $this->expect($exception->getFullAttributePath())->to()
                        ->equal($attributePath);

                    $this->expect($exception->getMissingAttributePath()->toString())->to()
                        ->equal('field.field_2');

                });

        });

    });

});
