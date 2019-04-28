<?php

namespace Haijin;

use Haijin\Errors\FileNotFoundError;
use Haijin\Haijin_Error;

class FilesCacheManifest
{
    protected $filepath;
    protected $cachedFiles;
    protected $fileHandle;

    /// Initializing

    public function __construct($filename)
    {
        $this->filepath = new FilePath($filename);
        $this->cachedFiles = new Dictionary();
        $this->fileHandle = null;

        $this->ensureManifestFileExists();
    }

    /// Locking

    protected function ensureManifestFileExists()
    {
        if (!$this->filepath->existsFile()) {
            $this->createFileManifest();
        }
    }

    protected function createFileManifest()
    {
        $this->write();
    }

    /// Writting

    public function write($prettyPrint = false)
    {
        $this->ensureManifestFolderExists();

        $jsonContents = json_encode(
            $this->cachedFiles->toArray(),
            $prettyPrint ? JSON_PRETTY_PRINT : 0
        );

        $this->filepath->writeFileContents($jsonContents);

        return $this;
    }

    protected function ensureManifestFolderExists()
    {
        if (!$this->existsManifestFolder()) {
            $this->createManifestFolder();
        }
    }

    protected function existsManifestFolder()
    {
        return $this->filepath->back()->existsDirectory();
    }

    protected function createManifestFolder()
    {
        mkdir($this->filepath->back()->toString(), 0777, true);
    }

    /// Reading

    public function lock()
    {
        $this->fileHandle = fopen($this->filepath->toString(), "r+");

        if ($this->fileHandle === false) {
            throw new Haijin_Error("Unable to open manifest file.");
        }

        if (flock($this->fileHandle, LOCK_EX) === false) {
            throw new Haijin_Error("Unable to lock manifest file.");
        }
    }

    public function unlock()
    {
        flock($this->fileHandle, LOCK_UN);
        fclose($this->fileHandle);
    }

    public function atPut($sourceFilename, $filename)
    {
        $sourceFilename = new FilePath($sourceFilename);
        $filepath = new FilePath($filename);

        $this->cachedFiles->atPut(
            $sourceFilename->toString(),
            [
                "filepath" => $filepath->toString(),
                "modificationTime" => $sourceFilename->getFileModificationTime(),
                "cachedAt" => time()
            ]
        );
    }

    /// Asking

    public function getCachedPathOf($sourceFilename)
    {
        return $this->cachedFiles->atIfAbsent($sourceFilename, function ()
        use ($sourceFilename) {

            $this->raiseMissingCachedFileError($sourceFilename);

        })["filepath"];
    }

    protected function raiseMissingCachedFileError($sourceFilename)
    {
        throw new FileNotFoundError(
            "The file \"$sourceFilename\" was not found in the cache directory.",
            $sourceFilename
        );
    }

    public function read()
    {
        $this->cachedFiles = Dictionary::withAll(
            json_decode($this->filepath->readFileContents(), true)
        );

        return $this;
    }

    public function needsCaching($sourceFilename)
    {
        return !$this->isCached($sourceFilename) || $this->isOutdated($sourceFilename);
    }

    public function isCached($sourceFilename)
    {
        return $this->cachedFiles->hasKey($sourceFilename);
    }

    /// Raising errors

    public function isOutdated($sourceFilename)
    {
        $sourcePath = new FilePath($sourceFilename);

        return $this->cachedFiles[$sourceFilename]["modificationTime"]
            <
            $sourcePath->getFileModificationTime();
    }
}