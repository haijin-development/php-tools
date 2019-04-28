<?php

use Haijin\Errors\MissingAttributeError;
use Haijin\ObjectAttributeAccessor;

$spec->describe("An ObjectAttributeAccessor", function () {

    $this->describe('with arrays', function () {

        $this->it("returns if an attribute is defined or not", function () {

            $object = [
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => [
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect($accessor->isDefined("address.street"))->to()->be()->true();
            $this->expect($accessor->notDefined("address.number"))->to()->be()->true();

            $this->expect($accessor->isDefined("address.number"))->to()->be()->false();
            $this->expect($accessor->notDefined("address.street"))->to()->be()->false();

        });

        $this->it("gets an existing attribute", function () {

            $object = [
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => [
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect($accessor->getValueAt("name"))->to()->equal("Lisa");

            $this->expect($accessor->getValueAt("address.street"))->to()
                ->equal("Evergreen 742");

        });

        $this->it("gets an existing attribute or evaluates an absent callable", function () {
            $object = [
                "name" => "Lisa",
                "lastName" => null,
                "address" => [
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect(

                $accessor->getValueAtIfAbsent("address.number", function () {
                    return "Absent value";
                })

            )->to()->equal("Absent value");

            $this->expect(

                $accessor->getValueAtIfAbsent("address.number", "Absent value")

            )->to()->equal("Absent value");


            $accessor = new ObjectAttributeAccessor($object);

            $this->expect(

                $accessor->getValueAtIfAbsent("lastName", "Absent value")

            )->to()->equal("Absent value");

        });

        $this->it("raises an error when trying to get an inexisting attribute", function () {

            $object = [
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => [
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect(function () use ($accessor) {

                $accessor->getValueAt("address.number");

            })->to()->raise(
                MissingAttributeError::class,
                function ($error) use ($object) {

                    $this->expect($error->getMessage())->to()
                        ->equal('The nested attribute "address.number" was not found.');

                    $this->expect($error->getFullAttributePath())->to()
                        ->equal("address.number");

                    $this->expect($error->getObject())->to()->be("===")
                        ->than($object);

                });

        });

        $this->it("sets an existing attribute", function () {
            $object = [
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => [
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);
            $accessor->setValueAt("address.street", "Evergreen");

            $this->expect($object["address"]["street"])->to()->equal("Evergreen");

        });

        $this->it("raises an error when trying to set an inexisting attribute", function () {

            $object = [
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => [
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect(function () use ($accessor) {

                $accessor->setValueAt("address.number", 742);

            })->to()->raise(
                MissingAttributeError::class,
                function ($error) use ($object) {

                    $this->expect($error->getMessage())->to()
                        ->equal('The nested attribute "address.number" was not found.');

                    $this->expect($error->getFullAttributePath())->to()
                        ->equal("address.number");

                    $this->expect($object, $error->getObject())->to()->be("===")
                        ->than($object);

                });

        });

        $this->it("creates and sets an inexisting attribute", function () {
            $object = [
                "name" => "Lisa",
                "lastName" => "Simpson",
            ];

            $accessor = new ObjectAttributeAccessor($object);
            $accessor->createValueAt("addresses.[0].address.number", 742);

            $this->expect($object["addresses"][0]["address"]["number"])->to()
                ->equal(742);

        });

        $this->it("does not override existing attribute when creating inexisting attribute", function () {
            $object = [
                "name" => "Lisa",
                "lastName" => "Simpson",
                "addresses" => [
                    [
                        "address" => [
                            "street" => "Evergreen"
                        ]
                    ]
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);
            $accessor->createValueAt("addresses.[0].address.number", 742);

            $this->expect($object["addresses"][0]["address"]["street"])->to()
                ->equal("Evergreen");

            $this->expect($object["addresses"][0]["address"]["number"])->to()
                ->equal(742);

        });

    });

    $this->describe('with object properties', function () {

        $this->it("returns if an attribute is defined or not", function () {

            $object = (object)[
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => (object)[
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect($accessor->isDefined("address.street"))->to()->be()->true();
            $this->expect($accessor->notDefined("address.number"))->to()->be()->true();

            $this->expect($accessor->isDefined("address.number"))->to()->be()->false();
            $this->expect($accessor->notDefined("address.street"))->to()->be()->false();

        });

        $this->it("gets an existing attribute", function () {

            $object = (object)[
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => (object)[
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect($accessor->getValueAt("name"))->to()->equal("Lisa");

            $this->expect($accessor->getValueAt("address.street"))->to()
                ->equal("Evergreen 742");

        });

        $this->it("gets an existing attribute or evaluates an absent callable", function () {
            $object = (object)[
                "name" => "Lisa",
                "lastName" => null,
                "address" => (object)[
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect(

                $accessor->getValueAtIfAbsent("address.number", function () {
                    return "Absent value";
                })

            )->to()->equal("Absent value");

            $this->expect(

                $accessor->getValueAtIfAbsent("address.number", "Absent value")

            )->to()->equal("Absent value");


            $accessor = new ObjectAttributeAccessor($object);

            $this->expect(

                $accessor->getValueAtIfAbsent("lastName", "Absent value")

            )->to()->equal("Absent value");

        });

        $this->it("raises an error when trying to get an inexisting attribute", function () {

            $object = (object)[
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => (object)[
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect(function () use ($accessor) {

                $accessor->getValueAt("address.number");

            })->to()->raise(
                MissingAttributeError::class,
                function ($error) use ($object) {

                    $this->expect($error->getMessage())->to()
                        ->equal('The nested attribute "address.number" was not found.');

                    $this->expect($error->getFullAttributePath())->to()
                        ->equal("address.number");

                    $this->expect($error->getObject())->to()->be("===")
                        ->than($object);

                });

        });

        $this->it("sets an existing attribute", function () {
            $object = (object)[
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => (object)[
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);
            $accessor->setValueAt("address.street", "Evergreen");

            $this->expect($object->address->street)->to()->equal("Evergreen");

        });

        $this->it("raises an error when trying to set an inexisting attribute", function () {

            $object = (object)[
                "name" => "Lisa",
                "lastName" => "Simpson",
                "address" => (object)[
                    "street" => "Evergreen 742"
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);

            $this->expect(function () use ($accessor) {

                $accessor->setValueAt("address.number", 742);

            })->to()->raise(
                MissingAttributeError::class,
                function ($error) use ($object) {

                    $this->expect($error->getMessage())->to()
                        ->equal('The nested attribute "address.number" was not found.');

                    $this->expect($error->getFullAttributePath())->to()
                        ->equal("address.number");

                    $this->expect($object, $error->getObject())->to()->be("===")
                        ->than($object);

                });

        });

        $this->it("creates and sets an inexisting attribute", function () {
            $object = (object)[
                "name" => "Lisa",
                "lastName" => "Simpson",
            ];

            $accessor = new ObjectAttributeAccessor($object);
            $accessor->createValueAt("addresses.[0].address.number", 742);

            $this->expect($object->addresses[0]->address->number)->to()->equal(742);

        });

        $this->it("does not override existing attribute when creating inexisting attribute", function () {

            $object = (object)[
                "name" => "Lisa",
                "lastName" => "Simpson",
                "addresses" => [
                    (object)[
                        "address" => (object)[
                            "street" => "Evergreen"
                        ]
                    ]
                ]
            ];

            $accessor = new ObjectAttributeAccessor($object);
            $accessor->createValueAt("addresses.[0].address.number", 742);

            $this->expect($object->addresses[0]->address->street)->to()
                ->equal("Evergreen");

            $this->expect($object->addresses[0]->address->number)->to()
                ->equal(742);

        });

    });

});