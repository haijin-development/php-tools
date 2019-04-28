<?php

use Haijin\Errors\FileNotFoundError;
use Haijin\Errors\HaijinError;
use Haijin\FilePath;
use Haijin\FilesCache;


$spec->describe("A FilesCache", function () {

    $this->beforeEach(function () {

        (new FilePath($this->cacheFolder))->delete();
        (new FilePath("tests/tmp"))->delete();

    });

    $this->afterAll(function () {

        (new FilePath($this->cacheFolder))->delete();
        (new FilePath("tests/tmp"))->delete();

    });

    $this->let("cacheFolder", function () {

        return "tests/cache";

    });

    $this->let("sourceFile", function () {

        return "tests/fileSamples/fileSample.txt";

    });

    $this->let("targetFileWithValidAbsolutePath", function () {

        return (new FilePath(getcwd()))->concat($this->cacheFolder)
            ->concat("fileSamples/fileSample.txt");

    });

    $this->let("targetFileWithInvalidAbsolutePath", function () {

        return "/directory-with-no-common-path-with-cache-directory/fileSample.txt";

    });

    $this->let("cache", function () {

        return (new FilesCache())
            ->setCacheFolder($this->cacheFolder);

    });

    $this->describe("when creating a new one", function () {

        $this->it("has default values", function () {

            $cache = new FilesCache();

            $this->expect($cache->getCacheFolder())->to()->be()->null();

            $this->expect($cache->getRealCacheFolder())->to()->be()->null();

            $this->expect($cache->getManifestFilename())->to()->be()->null();

            $this->expect($cache->getManifestPrettyPrint())->to()->be()->false();

        });

    });

    $this->describe("when configuring", function () {

        $this->let("cache", function () {

            return new FilesCache();

        });

        $this->describe("its cache directory with a relative path", function () {

            $this->it("sets the cache directory", function () {

                $this->cache->setCacheFolder("tests/../tests/cache");

                $this->expect($this->cache->getCacheFolder()->toString())
                    ->to()->equal("tests/../tests/cache");

            });

            $this->it("sets the real cache directory", function () {

                $this->cache->setCacheFolder("tests/../tests/cache");

                $this->expect($this->cache->getRealCacheFolder()->toString())
                    ->to()->match('|^/.+tests/cache$|');

            });

            $this->it("defines a default manifest filename", function () {

                $this->cache->setCacheFolder("tests/../tests/cache");

                $this->expect($this->cache->getManifestFilename()->toString())
                    ->to()->match('|^/.+tests/cache/cachedFileManifest.json$|');

            });

        });

        $this->describe("its cache directory with an asbolute path", function () {

            $this->it("sets the cache directory", function () {

                $this->cache->setCacheFolder("/absolute-path/tests/cache");

                $this->expect($this->cache->getCacheFolder())
                    ->to()->equal("/absolute-path/tests/cache");

            });

            $this->it("sets the real cache directory", function () {

                $this->cache->setCacheFolder("/absolute-path/tests/cache");

                $this->expect($this->cache->getRealCacheFolder()->toString())
                    ->to()->equal("/absolute-path/tests/cache");

            });

            $this->it("defines a default manifest filename", function () {

                $this->cache->setCacheFolder("/absolute-path/tests/cache");

                $this->expect($this->cache->getManifestFilename()->toString())->to()
                    ->equal('/absolute-path/tests/cache/cachedFileManifest.json');

            });

        });

        $this->it("its file manifest", function () {

            $this->cache->setManifestFilename("tests/tmp/another-directory/manifest.txt");

            $this->cache->setCacheFolder("tests/cache");

            $this->expect($this->cache->getCacheFolder()->toString())
                ->to()->equal("tests/cache");

            $this->expect($this->cache->getManifestFilename()->toString())
                ->to()->equal("tests/tmp/another-directory/manifest.txt");

        });

        $this->it("its manifest json pretty pring", function () {

            $this->cache->setManifestPrettyPrint(true);

            $this->expect($this->cache->getManifestPrettyPrint())
                ->to()->be()->true();

        });

    });

    $this->describe("when writting a cached file contents", function () {

        $this->it("gets the real cached file path", function () {

            $cachedPath = $this->cache->lockingDo(function ($cache) {

                $cache->writeFileContents(
                    $this->sourceFile,
                    "123",
                    "fileSample.txt"
                );

                return $cache->getPathOf($this->sourceFile);

            });

            $this->expect($cachedPath->toString())->to()->match(
                '|^/.+/tests/cache/fileSample.txt$|'
            );

        });

        $this->it("gets the real cached file path of a FilePath", function () {

            $cachedPath = $this->cache->lockingDo(function ($cache) {

                $cache->writeFileContents(
                    $this->sourceFile,
                    "123",
                    "fileSample.txt"
                );

                return $cache->getPathOf(new FilePath($this->sourceFile));

            });

            $this->expect($cachedPath->toString())->to()->match(
                '|^/.+/tests/cache/fileSample.txt$|'
            );

        });

        $this->it("writes the cached file contents with a path relative to the cache directory", function () {

            $cachedPath = $this->cache->lockingDo(function ($cache) {

                $cache->writeFileContents(
                    $this->sourceFile,
                    "123",
                    "fileSample.txt"
                );

                return $cache->getPathOf($this->sourceFile);

            });

            $this->expect($cachedPath->toString())->to()->haveFileContents(
                function ($contents) {

                    $this->expect($contents)->to()->equal("123");

                });

        });

        $this->it("raises an error without a lock", function () {

            $this->expect(function () {

                $this->cache->writeFileContents(
                    $this->sourceFile,
                    "123",
                    "fileSample.txt"
                );

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "To perform this operation must acquire a lock with 'lockingDo(\$callable)'."
                    );
                });

        });

        $this->it("writes the cached file contents with an absolute path within the cache directory", function () {

            $cachedPath = $this->cache->lockingDo(function ($cache) {

                $cache->writeFileContents(
                    $this->sourceFile,
                    "123",
                    $this->targetFileWithValidAbsolutePath
                );

                return $cache->getPathOf($this->sourceFile);

            });

            $this->expect($cachedPath->toString())->to()->match(
                '|^/.+/tests/cache/fileSamples/fileSample.txt$|'
            );

            $this->expect($cachedPath->toString())->to()->haveFileContents(
                function ($contents) {

                    $this->expect($contents)->to()->equal("123");

                });

        });

        $this->it("raises an error if the absolute path is not within the cache directory", function () {

            $this->expect(function () {

                $cachedPath = $this->cache->lockingDo(function ($cache) {

                    $cache->writeFileContents(
                        $this->sourceFile,
                        "123",
                        $this->targetFileWithInvalidAbsolutePath
                    );

                });

            })->to()->raise(
                HaijinError::class,
                function ($error) {

                    $this->expect($error->getMessage())->to()->match(
                        "|Trying to write to the cache the file '/directory-with-no-common-path-with-cache-directory/fileSample.txt with an absolute path outside the cache directory '/.+/tests/cache'.|"
                    );

                });

        });

    });

    $this->describe("when copying to a cached file", function () {

        $this->it("gets the cached file path", function () {

            $cachedPath = $this->cache->lockingDo(function ($cache) {

                $cache->writeFile(
                    $this->sourceFile,
                    "fileSample.txt"
                );

                return $cache->getPathOf($this->sourceFile);

            });

            $this->expect($cachedPath->toString())->to()->match(
                '|^/.+/tests/cache/fileSample.txt$|'
            );

        });

        $this->it("writes the cached file contents", function () {

            $cachedPath = $this->cache->lockingDo(function ($cache) {

                $cache->writeFile(
                    $this->sourceFile,
                    "fileSample.txt"
                );

                return $cache->getPathOf($this->sourceFile);

            });

            $this->expect($cachedPath->toString())->to()->haveFileContents(function ($contents) {

                $this->expect($contents)->to()->equal("Sample");

            });

        });

        $this->it("raises an error if the absolute path is not within the cache directory", function () {

            $this->expect(function () {

                $cachedPath = $this->cache->lockingDo(function ($cache) {

                    $cache->writeFile(
                        $this->sourceFile,
                        $this->targetFileWithInvalidAbsolutePath
                    );

                });

            })->to()->raise(
                HaijinError::class,
                function ($error) {

                    $this->expect($error->getMessage())->to()->match(
                        "|Trying to write to the cache the file '/directory-with-no-common-path-with-cache-directory/fileSample.txt with an absolute path outside the cache directory '/.+/tests/cache'.|"
                    );

                });

        });

        $this->it("raises an error without a lock", function () {

            $this->expect(function () {

                $this->cache->writeFile(
                    $this->sourceFile,
                    "fileSample.txt"
                );

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "To perform this operation must acquire a lock with 'lockingDo(\$callable)'."
                    );
                });

        });

    });

    $this->describe("when checking if a source file needs to be cached", function () {

        $this->it("returns true if the source file is not cached", function () {

            $needsCaching = $this->cache->lockingDo(function ($cache) {

                return $cache->needsCaching($this->sourceFile);

            });

            $this->expect($needsCaching)->to()->be()->true();

        });

        $this->it("returns false if the source file is cached", function () {

            $needsCaching = $this->cache->lockingDo(function ($cache) {

                $cache->writeFileContents(
                    $this->sourceFile,
                    "123",
                    "fileSample.txt"
                );

                return $cache->needsCaching($this->sourceFile);

            });

            $this->expect($needsCaching)->to()->be()->false();

        });

        $this->it("returns true if the source file is outdated", function () {

            $needsCaching = $this->cache->lockingDo(function ($cache) {

                $this->cache->writeFileContents(
                    $this->sourceFile,
                    "123",
                    "subdirectory/fileSample.txt"
                );

                touch($this->sourceFile);

                return $cache->needsCaching($this->sourceFile);

            });

            $this->expect($needsCaching)->to()->be()->true();

        });

        $this->it("does not fail with FilePath parameters", function () {

            $needsCaching = $this->cache->lockingDo(function ($cache) {

                return $cache->needsCaching(new FilePath($this->sourceFile));

            });

            $this->expect($needsCaching)->to()->be()->true();

        });

        $this->it("raises an error without a lock", function () {

            $this->expect(function () {

                $this->cache->needsCaching(new FilePath($this->sourceFile));

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "To perform this operation must acquire a lock with 'lockingDo(\$callable)'."
                    );
                });

        });

    });

    $this->describe("when locking", function () {

        $this->it("creates the manifest directory if absent", function () {

            $this->cache->setManifestFilename(
                "tests/tmp/another-directory/manifest.txt"
            );

            $this->cache->lockingDo(function ($cache) {

            });

            $this->expect("tests/tmp/another-directory/")
                ->to()->be()->aDirectory();

        });

        $this->it("it is reentrant", function () {

            $needsCaching = $this->cache->lockingDo(function ($cache) {

                return $this->cache->lockingDo(function ($cache) {

                    return $cache->needsCaching($this->sourceFile);

                });

            });

            $this->expect($needsCaching)->to()->be()->true();

        });

        $this->it("raises an error if the cached file is missing", function () {

            $this->expect(function () {

                $this->cache->lockingDo(function ($cache) {

                    $cache->getPathOf($this->sourceFile);

                });

            })->to()->raise(
                FileNotFoundError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "The file \"$this->sourceFile\" was not found in the cache directory."
                    );

                    $this->expect($error->getFilename())->to()
                        ->equal($this->sourceFile);
                }
            );

        });

        $this->it("raises an error if the cached directory is not defined", function () {

            $this->expect(function () {

                $this->cache->setCacheFolder(null);

                $this->cache->lockingDo(function ($cache) {

                });

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "The cache directory is empty. Configure it by calling ->setCacheFolder( \$directory );"
                    );
                }
            );

        });

        $this->it("raises an error if the cached directory is not defined", function () {

            $this->expect(function () {

                $this->cache->setManifestFilename(null);

                $this->cache->lockingDo(function ($cache) {

                });

            })->to()->raise(
                HaijinError::class,
                function ($error) {
                    $this->expect($error->getMessage())->to()->equal(
                        "The manifest file name is empty. Configure it by calling ->setManifestFilename( \$filename );"
                    );
                }
            );

        });

    });

});