<?php

namespace Copona\System\Library\Extension;

abstract class ExtensionBase
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Type Extension
     * @return TypeInterface
     */
    public abstract function getType();

    public function isEnable()
    {
        /** /Registry */
        global $registry;

        return (boolean)$registry->get('config')->get($this->getName() . '_status');
    }

    /**
     * Register Cronjob
     * @return array
     */
    public function registerCronjob()
    {
        return [];
    }
}