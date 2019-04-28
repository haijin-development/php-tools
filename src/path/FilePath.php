<?php

namespace Haijin;

use Haijin\Errors\CouldNotCreateDirectoryError;
use Haijin\Errors\FileNotFoundError;
use Haijin\Errors\HaijinError;
use Haijin\Errors\PathError;


/**
 * Models a file path.
 *
 * Examples:
 *
 *      /// Creating paths
 *
 *      use Haijin\FilePath;
 *
 *      // Creates an empty path.
 *      $path = new FilePath();
 *
 *      // Creates a path from an attributes chain string.
 *      $path = new FilePath( 'home/dev/src' );
 *      print( $path . "\n" );
 *
 *      // Creates a path from an attributes chain array.
 *      $path = new FilePath( ['home', 'dev', 'src'] );
 *      print( $path . "\n" );
 *
 *      // Creates a path from another path.
 *      $path = new FilePath( new FilePath( 'home/dev/src' ) );
 *      print( $path . "\n" );
 *
 *      /// Concatenating paths
 *
 *      // Concatenates two paths into a new one.
 *      $path = new FilePath( 'home' );
 *      $newPath = $path->concat( new FilePath( 'dev/src' ) );
 *      print( $newPath . "\n" );
 *
 *      // Concatenates a string into a new FilePath.
 *      $path = new FilePath( 'home' );
 *      $newPath = $path->concat( 'dev/src' );
 *      print( $newPath . "\n" );
 *
 *      // Concatenates an array of attributes into a new FilePath.
 *      $path = new FilePath( 'home' );
 *      $newPath = $path->concat( ['dev/src'] );
 *      print( $newPath . "\n" );
 *
 *      /// Moving the path back
 *
 *      // Removes the last attribute from the path into a new path.
 *      $path = new FilePath( 'home/dev/src' );
 *      $newPath = $path->back();
 *      print( $newPath . "\n" );
 *
 *      // Removes the last n attributes from the path into a new path.
 *      $path = new FilePath( 'home/dev/src' );
 *      $newPath = $path->back( 2 );
 *      print( $newPath . "\n" );
 *
 *      /// Appending paths
 *
 *      // Appends another Path to a path.
 *      $path = new FilePath( 'home' );
 *      $path->append( new FilePath( 'dev/src' ) );
 *      print( $path . "\n" );
 *
 *      // Appends an attributes string to a path.
 *      $path = new FilePath( 'home' );
 *      $path->append( 'dev/src' );
 *      print( $path . "\n" );
 *
 *      // Appends an attributes array to a path.
 *      $path = new FilePath( 'home' );
 *      $path->append( ['dev/src'] );
 *      print( $path . "\n" );
 *
 *      /// Dropping path tails
 *
 *      // Drops the last attribute from the path.
 *      $path = new FilePath( 'home/dev/src' );
 *      $path->drop();
 *      print( $path . "\n" );
 *
 *      // Drops the last n attributes from the path.
 *      $path = new FilePath( 'home/dev/src' );
 *      $path->drop( 2 );
 *      print( $path . "\n" );
 *
 */
class FilePath extends Path
{
    /**
     * Some operative sistems begins with a protocol before the path (ej. C:/).
     */
    protected $protocol;

    /**
     * Initializes the instance
     */
    public function __construct($attributesChain = null, $isAbsolute = false)
    {
        $this->protocol = null;

        parent::__construct($attributesChain, $isAbsolute);
    }

    /**
     * Returns the string used as a separator between consecutive attributes when converting the Path
     * to a string.
     *
     * @return string The string used as a separator between consecutive attributes when converting the Path
     * to a string.
     */
    public function defaultSeparator()
    {
        return '/';
    }

    /// Constants

    public function getProtocol()
    {
        return $this->protocol;
    }

    /// Accessing

    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * Returns the extension of the file. Assumes the file name is the ending part of the path.
     *
     * @return string The name of the file at the end of the path.
     */
    public function getFileExtension()
    {
        $parts = explode('.', $this->getFileName());

        if (count($parts) == 1) {
            return '';
        }

        return $parts[count($parts) - 1];
    }

    /// Asking

    /**
     * Returns the name of the file. Assumes the file name is the ending part of the path.
     *
     * @return string The name of the file at the end of the path.
     */
    public function getFileName()
    {
        return $this->getLastAttribute();
    }

    /// Querying

    /**
     * Returns a new FilePath with the filename extension changed to $newExtension.
     *
     * @return FilePath The FilePath with the new extension.
     */
    public function changeExtensionTo($newExtension, $currentExtension = null)
    {
        if ($currentExtension === null) {

            $parts = explode('.', $this->getFileName());

            if (count($parts) == 1) {
                $parts[] = $newExtension;
            } else {
                $parts[count($parts) - 1] = $newExtension;
            }

            return $this->back()->concat(join('.', $parts));

        } else {

            $replacedPath =
                preg_replace(
                    "/{$currentExtension}(?!.*{$currentExtension})/",
                    $newExtension,
                    $this->toString()
                );

            return $this->newInstanceWith($replacedPath);

        }
    }

    /**
     * Returns the path as a string of attributes separated by dots.
     *
     * @param string $separator Optional - The string used between consecutives attributes. Defaults to ".".
     *
     * @return string The attributes path string.
     */
    public function toString($separator = null)
    {
        if ($separator === null) {
            $separator = $this->separator;
        }

        if ($this->protocol !== null) {

            return $this->protocol . '://' . parent::toString($separator);

        } elseif ($this->isAbsolute) {

            return $separator . parent::toString($separator);

        } else {

            return parent::toString($separator);

        }
    }

    /**
     * Returns the last modification time of the file.
     *
     * @return string The protocol of the file.
     */
    public function getFileModificationTime()
    {
        $this->validateFileExists();

        return filemtime($this->toString());
    }

    protected function validateFileExists()
    {
        if (!$this->existsFile()) {
            $this->raiseFileNotFoundError();
        }
    }

    public function existsFile()
    {
        return file_exists($this->toAbsolutePath()->toString())
            &&
            !is_dir($this->toAbsolutePath()->toString());
    }

    /**
     * If $this path is absolute returns $this. Otherwise returns the abolute
     * path from the current working directory.
     *
     * @return string  The contents of the file at $this FilePath.
     */
    public function toAbsolutePath()
    {
        if ($this->isAbsolute() || $this->hasProtocol()) {
            return $this->resolve();
        }

        return $this->newInstanceWith(getcwd())
            ->append($this)->resolve();
    }

    /// Comparing paths

    public function hasProtocol()
    {
        return $this->protocol !== null;
    }

    public function resolve()
    {
        if ($this->isRelative()) {
            $this->raiseCanNotResolveRelativePathError();
        }

        $path = $this->newInstanceWith([]);
        $path->beAbsolute($this->isAbsolute);
        $path->setProtocol($this->protocol);

        foreach ($this->toArray() as $directory) {

            if ($directory == '.') {

            } elseif ($directory == '..') {
                $path->drop();
            } else {
                $path->append($directory);
            }
        }

        return $path;
    }

    protected function raiseCanNotResolveRelativePathError()
    {
        throw new PathError(
            "Only absolute paths can be resolved. Can not resolve path '{$this}'."
        );
    }

    protected function raiseFileNotFoundError()
    {
        throw new FileNotFoundError(
            "File '{$this}' not found.",
            $this
        );
    }

    /// Files operations

    /**
     * Returns the file size in bytes.
     *
     * @return string The file size in bytes.
     */
    public function getFileSize()
    {
        $this->validateFileExists();

        return filesize($this->toString());
    }

    /**
     * Returns the difference between $this Path and another one.
     */
    public function differenceWith($anotherPath)
    {
        if (is_string($anotherPath) || is_array($anotherPath)) {
            $anotherPath = $this->newInstanceWith($anotherPath);
        }

        $this->validateSameProtocolWith(
            $anotherPath,
            "Trying to get the path difference between a path with protocol and a path with no protocol."
        );

        $this->validateSameAbsolutnessWith(
            $anotherPath,
            "Trying to get the path difference between an absolute path and a relative path."
        );

        $commonPath = parent::differenceWith($anotherPath);

        if ($this->protocol !== null) {
            $commonPath->setProtocol($this->protocol);
        }

        return $commonPath;
    }

    protected function validateSameProtocolWith($anotherPath, $errorMessage)
    {
        if ($this->protocol !== $anotherPath->getProtocol()) {
            throw new HaijinError($errorMessage);
        }
    }

    protected function validateSameAbsolutnessWith($anotherPath, $errorMessage)
    {
        if (
            ($this->isAbsolute() && $anotherPath->isRelative())
            ||
            ($this->isRelative() && $anotherPath->isAbsolute())
        ) {
            throw new HaijinError($errorMessage);
        }
    }

    /**
     * Returns true if $this path is subpath of $anotherPath.
     */
    public function beginsWith($anotherPath)
    {
        if (is_string($anotherPath) || is_array($anotherPath)) {
            $anotherPath = $this->newInstanceWith($anotherPath);
        }

        $this->validateSameProtocolWith(
            $anotherPath,
            "Trying to answer if a path with protocol begins with a path with no protocol."
        );

        $this->validateSameAbsolutnessWith(
            $anotherPath,
            "Trying to answer if an absolute path begins with a relative path."
        );

        return parent::beginsWith($anotherPath);
    }

    public function walkTo($anotherPath)
    {
        if (is_string($anotherPath) || is_array($anotherPath)) {
            $anotherPath = $this->newInstanceWith($anotherPath);
        }

        $walkPath = $this->newInstanceWith([]);

        $rootInCommon = $this->rootInCommonWith($anotherPath);

        if ($rootInCommon->isEmpty()) {

            $length = $this->length();

            for ($i = 0; $i < $length; $i++) {
                $walkPath->append('..');
            }

            $walkPath->append($anotherPath);

        } else {

            if ($this->length() <= $anotherPath->length()) {

                $walkPath->append($anotherPath->differenceWith($this));

            } else {

                $length = $this->length() - $rootInCommon->length();

                for ($i = 0; $i < $length; $i++) {
                    $walkPath->append('..');
                }

            }

        }

        return $walkPath;
    }

    /**
     * Returns the common root between $this path and another one.
     */
    public function rootInCommonWith($anotherPath)
    {
        if (is_string($anotherPath) || is_array($anotherPath)) {
            $anotherPath = $this->newInstanceWith($anotherPath);
        }

        $this->validateSameProtocolWith(
            $anotherPath,
            "Trying to get the common path between a path with protocol and a path with no protocol."
        );

        $this->validateSameAbsolutnessWith(
            $anotherPath,
            "Trying to get the common path between an absolute path and a relative path."
        );

        $commonPath = parent::rootInCommonWith($anotherPath);

        if ($this->isAbsolute) {
            $commonPath->beAbsolute();
        }

        if ($this->protocol !== null) {
            $commonPath->setProtocol($this->protocol);
        }

        return $commonPath;
    }

    /**
     * Touches the file to the current time.
     *
     * @return boolean False on error.
     */
    public function touchFile()
    {
        $this->validateFileExists();

        return touch($this->toString());
    }

    /**
     * Reads and returns the contents of the file at $this FilePath.
     *
     * @return string  The contents of the file at $this FilePath.
     */
    public function readFileContents()
    {
        $this->validateFileExists();

        return file_get_contents($this->toString());
    }

    /**
     * Writes the contents to the file at $this FilePath.
     *
     * @param string  The contents to write to the file at $this FilePath.
     */
    public function writeFileContents($contents)
    {
        if (!$this->back()->existsDirectory()) {
            $this->back()->createDirectoryPath();
        }

        return file_put_contents($this->toString(), $contents);
    }

    /**
     * Recursively creates a subdirectory tree from $this FilePath.
     */
    public function createDirectoryPath($permissions = 0777)
    {
        if ($this->existsDirectory()) {
            return true;
        }

        if (false === mkdir($this->toString(), $permissions, true)) {
            return $this->raiseCouldNotCreateDirectoryError();
        }
    }

    /// Displaying

    public function existsDirectory()
    {
        return is_dir($this->toAbsolutePath()->toString());
    }

    /// Validating

    protected function raiseCouldNotCreateDirectoryError()
    {
        throw new CouldNotCreateDirectoryError(
            "Could not create the directory '{$this}'.",
            $this
        );
    }

    /**
     * Deletes the file or the directory with all of its contents.
     */
    public function delete()
    {
        if ($this->existsDirectory()) {

            return $this->deleteDirectory();

        } elseif ($this->existsFile()) {

            return $this->deleteFile();

        }
    }

    /**
     * Recursively deletes the directory and its contents.
     */
    public function deleteDirectory()
    {
        $this->validateDirectoryExists();

        foreach ($this->getDirectoryContents() as $fileOrDirectory) {

            $fileOrDirectory->delete();

        }

        return rmdir($this->toString());
    }

    protected function validateDirectoryExists()
    {
        if (!$this->existsDirectory()) {
            $this->raiseDirectoryNotFoundError();
        }
    }

    /// Raising errors

    protected function raiseDirectoryNotFoundError()
    {
        throw new FileNotFoundError(
            "Directory '{$this}' not found.",
            $this
        );
    }

    /**
     * Returns the contents of the directory with the given pattern.
     * If no pattern is given returns all the files and directories that are direct
     * children from $this directory.
     */
    public function getDirectoryContents($searchPattern = "*")
    {
        $this->validateDirectoryExists();

        $files = glob($this->concat($searchPattern)->toString());

        $filePaths = [];

        foreach ($files as $file) {
            $filePaths[] = new self($file);
        }

        return $filePaths;
    }

    /**
     * Deletes the file.
     */
    public function deleteFile()
    {
        $this->validateFileExists();

        return unlink($this->toString());
    }

    /**
     * Takes a string, array or Path and converts it to an array of single attributes.
     *
     * @param string|array|Path $attributesChain The parameter to normalize.
     *
     * @return array The array of single attributes obtained from normalizing the $attributesChain parameter.
     */
    protected function normalizeAttributesChain($attributesChain)
    {
        if (is_string($attributesChain)) {

            $matches = [];

            if (preg_match('/^(.+):(\/.+)/', $attributesChain, $matches)) {
                $this->protocol = $matches[1];

                return parent::normalizeAttributesChain($matches[2]);
            }

        } elseif (is_object($attributesChain)) {
            $this->protocol = $attributesChain->getProtocol();
        }

        return parent::normalizeAttributesChain($attributesChain);
    }
}