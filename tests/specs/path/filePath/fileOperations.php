<?php

use Haijin\FilePath;
use Haijin\Errors\PathError;
use Haijin\Errors\FileNotFoundError;
use Haijin\Errors\CouldNotCreateDirectoryError;

$spec->describe( "A FilePath", function() {

    $this->beforeAll( function() {

        if( !$this->tmpFolder->existsDirectory() ) {

            $this->tmpFolder->createDirectoryPath();

        }

    });

    $this->afterEach( function() {

        $this->tmpFolder->delete();

    });

    $this->let( "tmpFolder", function() {

        return new FilePath( __DIR__ . "/../../../tmp/" );

    });

    $this->it( "answers if the file exists", function() {

        $filePath = new FilePath(__DIR__ . "/../../../fileSamples/fileSample.txt");

        $this->expect( $filePath->existsFile() ) ->to() ->be() ->true();

        $filePath = new FilePath(__DIR__ . "/../../../fileSamples/no-file.txt");

        $this->expect( $filePath->existsFile() ) ->to() ->be() ->false();

        $filePath = new FilePath(__DIR__ . "/../../../fileSamples");

        $this->expect( $filePath->existsFile() ) ->to() ->be() ->false();

    });

    $this->it( "answers if the directory exists", function() {

        $filePath = new FilePath(__DIR__ . "/../../../fileSamples");

        $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->true();

        $filePath = new FilePath( __DIR__ . "/../../../no-directory" );

        $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->false();

        $filePath = new FilePath(__DIR__ . "/../../../fileSamples/fileSample.txt");

        $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->false();

    });

    $this->it( "gets the file name", function() {

        $filePath = new FilePath(
            __DIR__ . "/../../../fileSamples/fileSample.txt"
        );

        $this->expect( $filePath->getFileName() ) ->to() ->equal( "fileSample.txt" );

    });

    $this->it( "gets the file extension", function() {

        $filePath = new FilePath(
            __DIR__ . "/../../../fileSamples/fileSample.txt"
        );

        $this->expect( $filePath->getFileExtension() ) ->to() ->equal( "txt" );


        $filePath = new FilePath(
            __DIR__ . "/../../../fileSamples/file-sample"
        );

        $this->expect( $filePath->getFileExtension() ) ->to() ->equal( "" );

    });

    $this->describe( "when getting the file modification time", function() {

        $this->it( "gets the file mofication time", function() {

            $filePath = new FilePath(
                __DIR__ . "/../../../fileSamples/fileSample.txt"
            );

            $this->expect( $filePath->getFileModificationTime() ) ->to()
                ->be() ->int();

            $this->expect( $filePath->getFileModificationTime() ) ->to()
                ->be( ">" ) ->than( 0 );

        });

        $this->it( "raises an error if the file does not exist", function() {

            $this->expect( function() {

                $filePath = new FilePath(
                    __DIR__ . "/../../../fileSamples/missingSample.txt"
                );

                $filePath->getFileModificationTime();

            }) ->to() ->raise(
                FileNotFoundError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->match(
                        "|File '.+/fileSamples/missingSample[.]txt' not found.|"
                    );
            });

        });

    });

    $this->describe( "when getting the file size", function() {

        $this->it( "gets the file size", function() {

            $filePath = new FilePath(
                __DIR__ . "/../../../fileSamples/fileSample.txt"
            );

            $this->expect( $filePath->getFileSize() ) ->to() ->equal( 6 );

        });

        $this->it( "raises an error if the file does not exist", function() {

            $this->expect( function() {

                $filePath = new FilePath(
                    __DIR__ . "/../../../fileSamples/missingSample.txt"
                );

                $filePath->getFileSize();

            }) ->to() ->raise(
                FileNotFoundError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->match(
                        "|File '.+/fileSamples/missingSample[.]txt' not found.|"
                    );
            });

        });

    });

    $this->describe( "when getting the file contents", function() {

        $this->it( "gets the file contents", function() {

            $filePath = new FilePath(
                __DIR__ . "/../../../fileSamples/fileSample.txt"
            );

            $this->expect( $filePath->readFileContents() ) ->to() ->equal( "Sample" );

        });

        $this->it( "raises an error if the file does not exist", function() {

            $this->expect( function() {

                $filePath = new FilePath(
                    __DIR__ . "/../../../fileSamples/missingSample.txt"
                );

                $filePath->readFileContents();

            }) ->to() ->raise(
                FileNotFoundError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->match(
                        "|File '.+/fileSamples/missingSample[.]txt' not found.|"
                    );
            });

        });

    });


    $this->describe( "when writting the file contents", function() {

        $this->it( "writes the file contents", function() {

            $filePath = $this->tmpFolder->concat( "new-file.txt" );

            $filePath->writeFileContents( "123" );

            $this->expect( $filePath->readFileContents() ) ->to()
                ->equal( "123" );

        });

        $this->it( "creates the directory if it does not exist", function() {

            $filePath = $this->tmpFolder->concat(
                "new-directory/yet-aonther-new-directory/new-file.txt"
            );

            $filePath->writeFileContents( "123" );

            $this->expect( $filePath->readFileContents() ) ->to()
                ->equal( "123" );

        });

    });

    $this->describe( "when touching a file", function() {

        $this->it( "touches the file", function() {

            $filePath = $this->tmpFolder->concat( "new-file.txt" );

            $filePath->writeFileContents( "123" );

            $filePath->touchFile();

            $creationTime = $filePath->getFileModificationTime();

            sleep( 1 );

            $filePath->touchFile();

            $this->expect( $filePath->getFileModificationTime() ) ->to()
                ->be( '>' ) ->than( $creationTime );

        });

        $this->it( "raises an error if the file does not exist", function() {

            $this->expect( function() {

                $filePath = $this->tmpFolder->concat( "missing-file.txt" );

                $filePath->touchFile();

            }) ->to() ->raise(
                FileNotFoundError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->match(
                        "|File '.+/missing-file[.]txt' not found.|"
                    );
            });

        });

    });

    $this->describe( "when creating a directory", function() {

        $this->it( "creates a recursive directory", function() {

            $filePath = $this->tmpFolder->concat( "subdirectory-1/subdirectory-2" );

            $filePath->createDirectoryPath();

            $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->true();

        });

        $this->it( "does not fail if the directory already exist", function() {

            $filePath = $this->tmpFolder->concat( "subdirectory-1/subdirectory-2" );

            $filePath->createDirectoryPath();

            $filePath->createDirectoryPath();

            $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->true();

        });

        $this->it( "raises an error if it can not create the directory", function() {

            $this->expect( function() {

                $filePath = new FilePath( '/dev/null/subdirectory-1/' );

                $filePath->createDirectoryPath();

            }) ->to() ->raise(
                CouldNotCreateDirectoryError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to()
                        ->equal( "Could not create the directory '/dev/null/subdirectory-1'." );
                }
            );

        });

    });

    $this->describe( "when deleting a file with deleteFile()", function() {

        $this->it( "deletes a file", function() {

            $filePath = $this->tmpFolder->concat( "missing-file.txt" );

            $filePath->writeFileContents( "123" );

            $filePath->deleteFile();

            $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->false();

        });

        $this->it( "raises an error if the file does not exist", function() {

            $this->expect( function() {

                $filePath = $this->tmpFolder->concat( "missing-file.txt" );

                $filePath->deleteFile();

            }) ->to() ->raise(
                FileNotFoundError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->match(
                        "|File '.+/missing-file[.]txt' not found.|"
                    );
            });

        });

    });

    $this->describe( "when deleting a file with delete()", function() {

        $this->it( "deletes a file", function() {

            $filePath = $this->tmpFolder->concat( "missing-file.txt" );

            $filePath->writeFileContents( "123" );

            $filePath->delete();

            $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->false();

        });

        $this->it( "does not fail if the file does not exist", function() {

            $filePath = $this->tmpFolder->concat( "missing-file.txt" );

            $filePath->delete();

        });

    });

    $this->describe( "when deleting a directory with deleteDirectory()", function() {

        $this->it( "deletes a recursive directory", function() {

            $filePath = $this->tmpFolder->concat( "subdirectory-1/subdirectory-2" );

            $filePath->createDirectoryPath();

            $filePath->back( 2 )->deleteDirectory();

            $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->false();

        });

        $this->it( "raises an error if the directory does not exist", function() {

            $this->expect( function() {

                $filePath = $this->tmpFolder->concat( "subdirectory-1/subdirectory-2" );

                $filePath->deleteDirectory();

            }) ->to() ->raise(
                FileNotFoundError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->match(
                        "|Directory '.+/subdirectory-1/subdirectory-2' not found.|"
                    );
            });

        });

    });

    $this->describe( "when deleting a directory with delete()", function() {

        $this->it( "deletes a recursive directory", function() {

            $filePath = $this->tmpFolder->concat( "subdirectory-1/subdirectory-2" );

            $filePath->createDirectoryPath();

            $filePath->back( 2 )->delete();

            $this->expect( $filePath->existsDirectory() ) ->to() ->be() ->false();

        });

        $this->it( "does not fail if the directory does not exist", function() {

            $filePath = $this->tmpFolder->concat( "subdirectory-1/subdirectory-2" );

            $filePath->delete();

        });

    });

    $this->describe( "when getting a directory contents", function() {

        $this->it( "gets the directory contents", function() {

            $this->tmpFolder->concat( "subdirectory-1/subdirectory-2" )
                ->createDirectoryPath();

            $this->tmpFolder->concat( "subdirectory-1/sample.txt" )
                ->writeFileContents( '123' );

            $contents = $this->tmpFolder->concat( "subdirectory-1" )
                            ->getDirectoryContents();

            $this->expect( count( $contents ) ) ->to() ->equal( 2 );

            $this->expect( $contents[ 0 ]->toString() ) ->to() ->match(
                "|.+/tmp/subdirectory-1/sample[.]txt|"
            );

            $this->expect( $contents[ 1 ]->toString() ) ->to() ->match(
                "|.+/tmp/subdirectory-1/subdirectory-2|"
            );

        });

        $this->it( "raises an error if the directory does not exist", function() {

            $this->expect( function() {

                $filePath = $this->tmpFolder->concat( "subdirectory-1" );

                $filePath->getDirectoryContents();

            }) ->to() ->raise(
                FileNotFoundError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->match(
                        "|Directory '.+/subdirectory-1' not found.|"
                    );
            });

        });

    });

    $this->describe( "when changing the file extension of a path", function() {

        $this->it( "changes the file extension of a path with an extension", function() {

            $filePath = new FilePath( 'file-samples/file-sample.less' );

            $newFilePath = $filePath->changeExtensionTo( 'css' );

            $this->expect( $newFilePath->toString() )->to() ->equal(
                'file-samples/file-sample.css'
            );

        });

        $this->it( "changes the file extension of a path with no extension", function() {

            $filePath = new FilePath( 'file-samples/file-sample' );

            $newFilePath = $filePath->changeExtensionTo( 'css' );

            $this->expect( $newFilePath->toString() )->to() ->equal(
                'file-samples/file-sample.css'
            );

        });

        $this->it( "replaces the extension defined by a given pattern", function() {

            $filePath = new FilePath( 'file-samples/file-sample.js.sass.css.sass.css' );

            $newFilePath = $filePath->changeExtensionTo( 'css', 'sass.css' );

            $this->expect( $newFilePath->toString() )->to() ->equal(
                'file-samples/file-sample.js.sass.css.css'
            );

        });

    });

    $this->it( "converts a path to a real path", function() {

        $filePath = ( new FilePath( 'file-samples/file-sample' ) )
            ->toAbsolutePath();

        $this->expect( $filePath->toString() )->to() ->match(
            '|^/.+/file-samples/file-sample$|'
        );

        $filePath = ( new FilePath( 'http:://file-samples.org/file-sample' ) )
            ->toAbsolutePath();

        $this->expect( $filePath->toString() )->to() ->equal(
            'http:://file-samples.org/file-sample'
        );

    });

    $this->describe( "when resolving a path", function() {

        $this->it( "resolves the path to the real path if its absolute", function() {

            $filePath = $this->tmpFolder
                    ->concat( "subdirectory-1/../subdirectory-2/file.txt" );

            $this->expect( $filePath->resolve()->toString() ) ->to() ->match(
                '|.+/tests/tmp/subdirectory-2/file[.]txt|'
            );

        });

        $this->it( "raises an error if the path is relative", function() {

            $this->expect( function() {

                $filePath = new FilePath( "subdirectory-1/../subdirectory-2/file.txt" );

                $filePath->resolve();

            }) ->to() ->raise(
                PathError::class,
                function($error) {
                    $this->expect( $error->getMessage() ) ->to() ->match(
                        "|Only absolute paths can be resolved. Can not resolve path 'subdirectory-1/../subdirectory-2/file.txt'.|"
                    );
            });

        });

    });

});