<?php

namespace Copona\System\Library\Extension;

abstract class ExtensionBase
{
    /**
     * @var ExtensionItem
     */
    protected $extensionItem;

    /**
     * @var \Registry
     */
    protected $registry;

    public function __construct(ExtensionItem $extensionItem)
    {
        $this->extensionItem = $extensionItem;
        $this->registry = \Registry::getInstance();
    }

    /**
     * Executed before load controllers and views
     */
    public function onInit()
    {

    }

    /**
     * Define details about extension
     * @return array
     */
    public abstract function details();

    /**
     * Get detail extension
     *
     * @param $key
     * @return mixed|null
     */
    public function getDetail($key)
    {
        return isset($this->details()[$key]) ? $this->details()[$key] : null;
    }

    /**
     * Get Name extension
     *
     * @return string
     */
    public function getName()
    {
        return $this->detail('name')
            ? $this->detail('name')
            : $this->extensionItem->getName();
    }

    /**
     * Check extension is enable
     *
     * @return bool
     */
    public function isEnable()
    {
        return (boolean)$this->registry->get('config')->get($this->getName() . '_status');
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