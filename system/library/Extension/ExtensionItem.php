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
     * @var string
     */
    public $vendor;

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var ExtensionBase
     */
    public $instance;

    /**
     * List all files inside extension
     *
     * @var array
     */
    public $files = [];

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
}