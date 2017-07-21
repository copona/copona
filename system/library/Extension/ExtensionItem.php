<?php

namespace Copona\System\Library\Extension;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ExtensionItem
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var
     */
    public $vendor;

    /**
     * @var ExtensionBase
     */
    protected $instance;

    /**
     * @var SplFileInfo
     */
    public $path;

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var Finder
     */
    public $finder;

    public $files = [];

    /**
     * Get instance Extension Class
     *
     * @return ExtensionBase
     * @throws \Exception
     */
    public function getIntance()
    {
        $this->instance = new $this->namespace($this->name);
        if (($this->instance instanceof ExtensionBase) == false) {
            throw new \Exception($this->namespace . ' is not instance of ' . ExtensionBase::class);
        }

        return $this->instance;
    }
}