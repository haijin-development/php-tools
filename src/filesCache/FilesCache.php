<?php

namespace Haijin;

use Haijin\Errors\HaijinError;

class FilesCache
{
    protected $cacheFolder;
    protected $manifestFilename;
    protected $manifest;
    protected $manifestPrettyPrint;

    /// Initializing

    public function __construct()
    {
        $this->cacheFolder = null;
        $this->realCacheFolder = null;
        $this->manifestFilename = null;
        $this->manifest = null;
        $this->manifestPrettyPrint = false;
    }

    /// Accessing

    public function getCacheFolder()
    {
        if ($this->cacheFolder !== null) {
            return $this->cacheFolder;
        }

        return null;
    }

    public function setCacheFolder($directory)
    {
        if ($directory === null) {
            $this->cacheFolder = null;
            $this->realCacheFolder = null;
            return;
        }

        $this->cacheFolder = new FilePath($directory);

        $this->realCacheFolder = $this->cacheFolder->toAbsolutePath();

        if ($this->manifestFilename === null) {

            $this->setDefaultManifestFolder();

        }

        return $this;
    }

    protected function setDefaultManifestFolder()
    {
        $this->setManifestFilename(
            $this->realCacheFolder->concat("cachedFileManifest.json")
        );
    }

    public function getRealCacheFolder()
    {
        return $this->realCacheFolder;
    }

    public function getManifestFilename()
    {
        if ($this->manifestFilename !== null) {
            return $this->manifestFilename;
        }

        return null;
    }

    public function setManifestFilename($filename)
    {
        $this->manifestFilename = $filename === null ? null : new FilePath($filename);

        return $this;
    }

    public function getManifestPrettyPrint()
    {
        return $this->manifestPrettyPrint;
    }

    public function setManifestPrettyPrint($boolean)
    {
        $this->manifestPrettyPrint = $boolean;
    }

    /// Caching

    public function writeFileContents($sourceFilename, $contents, $targetPath)
    {
        $this->validateLock();

        if (is_string($sourceFilename)) {
            $sourceFilename = new FilePath($sourceFilename);
        }

        if (is_string($targetPath)) {
            $targetPath = new FilePath($targetPath);
        }

        $sourceFilename = $sourceFilename;
        $targetPath = $targetPath;

        if ($targetPath->isRelative()) {

            $targetPath = $this->realCacheFolder->concat($targetPath);

        } else {

            if (!$targetPath->beginsWith($this->realCacheFolder)) {
                throw new HaijinError(
                    "Trying to write to the cache the file '{$targetPath} with an absolute path outside the cache directory '{$this->realCacheFolder}'."
                );
            }

        }

        $this->ensureCacheFileFolderExists($targetPath);

        $targetPath->writeFileContents($contents);

        $this->setPathOf($sourceFilename, $targetPath);

        $this->manifest->write($this->manifestPrettyPrint);
    }

    protected function validateLock()
    {
        if ($this->manifest !== null) {
            return;
        }

        return $this->raiseMissingLockingError();
    }

    protected function raiseMissingLockingError()
    {
        throw new HaijinError(
            "To perform this operation must acquire a lock with 'lockingDo(\$callable)'."
        );
    }

    public function ensureCacheFileFolderExists($cachedFilename)
    {
        if (!$cachedFilename->back()->existsDirectory()) {
            $cachedFilename->back()->createDirectoryPath();
        }
    }

    /// Querying

    public function setPathOf($sourceFilename, $cachedFilename)
    {
        $this->validateLock();

        if (!is_string($sourceFilename)) {
            $sourceFilename = $sourceFilename->toString();
        }

        $this->manifest->atPut($sourceFilename, $cachedFilename);

        $this->manifest->write($this->manifestPrettyPrint);
    }

    public function writeFile($sourceFilename, $targetPath)
    {
        $this->validateLock();

        if (is_string($targetPath)) {
            $targetPath = new FilePath($targetPath);
        }

        if ($targetPath->isRelative()) {

            $targetPath = $this->realCacheFolder->concat($targetPath);

        } else {

            if (!$targetPath->beginsWith($this->realCacheFolder)) {
                throw new HaijinError(
                    "Trying to write to the cache the file '{$targetPath} with an absolute path outside the cache directory '{$this->realCacheFolder}'."
                );
            }

        }

        $this->ensureCacheFileFolderExists($targetPath);

        copy($sourceFilename, $targetPath->toString());

        $this->setPathOf($sourceFilename, $targetPath);

        $this->manifest->write($this->manifestPrettyPrint);
    }

    public function needsCaching($sourceFilename)
    {
        $this->validateLock();

        if (!is_string($sourceFilename)) {
            $sourceFilename = $sourceFilename->toString();
        }

        return $this->manifest->needsCaching($sourceFilename);
    }

    /// Locking

    public function getPathOf($sourceFilename)
    {
        $this->validateLock();

        if (!is_string($sourceFilename)) {
            $sourceFilename = $sourceFilename->toString();
        }

        return new FilePath(
            $this->manifest->getCachedPathOf($sourceFilename)
        );
    }

    public function lockingDo($callable)
    {
        $this->validateCacheConfiguration();

        if ($this->manifest !== null) {
            return $callable($this);
        }

        $this->manifest = $this->getManifest();

        $this->manifest->lock();

        try {

            $this->manifest->read();

            return $callable($this);

        } finally {

            $this->manifest->unlock();

            $this->manifest = null;

        }
    }

    /// Validating

    protected function validateCacheConfiguration()
    {
        if ($this->realCacheFolder === null) {
            throw new HaijinError("The cache directory is empty. Configure it by calling ->setCacheFolder( \$directory );");
        }

        if ($this->manifestFilename === null) {
            throw new HaijinError("The manifest file name is empty. Configure it by calling ->setManifestFilename( \$filename );");
        }

        $this->ensureManifestFileFolderExists($this->manifestFilename);
    }

    protected function ensureManifestFileFolderExists($manifestFilename)
    {
        if (!$manifestFilename->back()->existsDirectory()) {
            $manifestFilename->back()->createDirectoryPath();
        }
    }

    /// Raising errors

    protected function getManifest()
    {
        return new FilesCacheManifest($this->manifestFilename);
    }
}