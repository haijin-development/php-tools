<?php

use Haijin\Object_Attribute_Accessor;
use Haijin\File_Path;
use Haijin\Files_Cache;

$spec->describe( "A Files_Cache", function() {

    $this->before_each( function() {

        ( new File_Path( $this->cache_folder ) )->delete();
        ( new File_Path( "tests/tmp" ) )->delete();

    });

    $this->after_all( function() {

        ( new File_Path( $this->cache_folder ) )->delete();
        ( new File_Path( "tests/tmp" ) )->delete();

    });

    $this->let( "cache_folder", function() {

        return "tests/cache";

    });

    $this->let( "cache", function() {

        return ( new Files_Cache() )
            ->set_cache_folder( $this->cache_folder );

    });

    $this->let( "source_file", function() {

        return "tests/file-samples/file-sample.txt";

    });

    $this->describe( "when configuring it", function() {

        $this->it( "defines its cache folder", function() {

            $this->cache->set_cache_folder( "tests/cache" );

            $this->expect( $this->cache->get_cache_folder() ) ->to() ->equal( "tests/cache" );

            $this->expect( $this->cache->get_manifest_filename() )
                    ->to() ->equal( "tests/cache/cached_file_manifest.json" );

        });

        $this->it( "defines its file manifest", function() {

            $this->cache->set_cache_folder( "tests/cache" );
            $this->cache->set_manifest_filename( "tests/tmp/another-folder/manifest.txt" );

            $this->expect( $this->cache->get_cache_folder() ) ->to() ->equal( "tests/cache" );

            $this->expect( $this->cache->get_manifest_filename() )
                    ->to() ->equal( "tests/tmp/another-folder/manifest.txt" );

            $this->expect( "tests/tmp/another-folder/" )
                    ->to() ->be() ->a_folder();

        });

    });

    $this->describe( "when storing a cached file contents", function() {

        $this->it( "gets the cached file path", function() {

            $cached_path = $this->cache->locking_do( function ($cache) {

                $cache->cache_file_contents(
                    $this->source_file,
                    "123",
                    "file-sample.txt"
                );

                return $cache->get_path_of( $this->source_file );

            }, $this );

            $this->expect( $cached_path ) ->to() ->equal(
                $this->cache_folder . "/file-sample.txt"
            );

        });

        $this->it( "writes the cached file contents", function() {

            $cached_path = $this->cache->locking_do( function ($cache) {

                $cache->cache_file_contents(
                    $this->source_file,
                    "123",
                    "file-sample.txt"
                );

                return $cache->get_path_of( $this->source_file );

            }, $this );

            $this->expect( $cached_path ) ->to() ->have_file_contents( function($contents) {

                $this->expect( $contents ) ->to() ->equal( "123" );

            });

        });

    });

    $this->describe( "when copying to a cached file", function() {

        $this->it( "gets the cached file path", function() {

            $cached_path = $this->cache->locking_do( function ($cache) {

                $cache->cache_file(
                    $this->source_file,
                    "file-sample.txt"
                );

                return $cache->get_path_of( $this->source_file );

            }, $this );

            $this->expect( $cached_path ) ->to() ->equal(
                $this->cache_folder . "/file-sample.txt"
            );

        });

        $this->it( "writes the cached file contents", function() {

            $cached_path = $this->cache->locking_do( function ($cache) {

                $cache->cache_file(
                    $this->source_file,
                    "file-sample.txt"
                );

                return $cache->get_path_of( $this->source_file );

            }, $this );

            $this->expect( $cached_path ) ->to() ->have_file_contents( function($contents) {

                $this->expect( $contents ) ->to() ->equal( "Sample" );

            });

        });

    });

    $this->describe( "when checking if a source file needs to be cached", function() {

        $this->it( "returns true if the source file is not cached", function() {

            $needs_caching = $this->cache->locking_do( function ($cache) {

                return $cache->needs_caching( $this->source_file );

            }, $this );

            $this->expect( $needs_caching ) ->to() ->be() ->true();

        });

        $this->it( "returns false if the source file is cached", function() {

            $needs_caching = $this->cache->locking_do( function ($cache) {

                $cache->cache_file_contents(
                    $this->source_file,
                    "123",
                    "file-sample.txt"
                );

                return $cache->needs_caching( $this->source_file );

            }, $this );

            $this->expect( $needs_caching ) ->to() ->be() ->false();

        });

        $this->it( "returns true if the source file is outdated", function() {

            $needs_caching = $this->cache->locking_do( function ($cache) {

                $this->cache->cache_file_contents(
                    $this->source_file,
                    "123",
                    "subfolder/file-sample.txt"
                );

                touch( $this->source_file );

                return $cache->needs_caching( $this->source_file );

            }, $this );

            $this->expect( $needs_caching ) ->to() ->be() ->true();

        });

    });

    $this->it( "locking is reentrant", function() {

        $needs_caching = $this->cache->locking_do( function ($cache) {

            return $this->cache->locking_do( function ($cache) {

                return $cache->needs_caching( $this->source_file );

            }, $this );

        }, $this );


        $this->expect( $needs_caching ) ->to() ->be() ->true();

    });

    $this->it( "raises an error if the cached file is missing", function() {

        $this->expect( function() {

            $this->cache->locking_do( function ($cache) {

                $cache->get_path_of( $this->source_file );

            }, $this );

        }) ->to() ->raise(
            \Haijin\Missing_Cached_File_Error::class,
            function($error) {
                $this->expect( $error->getMessage() ) ->to() ->equal(
                    "The file \"$this->source_file\" was not found in the cache folder."
                );

                $this->expect( $error->get_source_filename() ) ->to()
                    ->equal( $this->source_file );
            }
        );

    });

});