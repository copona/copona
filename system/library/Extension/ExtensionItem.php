<?php

namespace Copona\System\Library\Extension;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ExtensionItem
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var ExtensionBase
     */
    protected $instance;

    /**
     * @var SplFileInfo
     */
    protected $path;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * List all files inside extension
     *
     * @var array
     */
    protected $files = [];

    /**
     * Get instance Extension Class
     *
     * @return ExtensionBase
     * @throws \Exception
     */
    public function getIntance()
    {
        if(!$this->instance) {

            $extensionClass = $this->namespace . '\\Extension';

            $this->instance = new $extensionClass($this);

            if (($this->instance instanceof ExtensionBase) == false) {
                throw new \Exception($this->namespace . ' is not instance of ' . ExtensionBase::class);
            }
        }

        return $this->instance;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return SplFileInfo
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param SplFileInfo $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return Finder
     */
    public function getFinder()
    {
        return $this->finder;
    }

    /**
     * @param Finder $finder
     */
    public function setFinder($finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->files[] = $file;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }
}