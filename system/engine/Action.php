<?php

namespace Copona\System\Engine;

use Copona\Exception\ActionException;
use Copona\System\Library\Extension\ExtensionManager;

class Action
{
    private $id;
    private $method = 'index';
    public $extension_file;

    public function __construct($route)
    {
        $this->id = $route;

        // Break apart the route
        $parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route));

        $info_file = $this->prepareController($parts);
        $this->file = $info_file->file;
        $this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $info_file->supposed_class);
        $this->method = $info_file->supposed_method;
    }

    public function getId()
    {
        return $this->id;
    }

    public function execute($registry, Array &$args = [])
    {
        // Stop any magical methods being called
        if (substr($this->method, 0, 2) == '__') {
            return false;
        }

        if (is_file($this->file)) {
            include_once($this->file);

            $class = $this->class;

            $controller = new $class($registry);

            if (is_callable([$controller, $this->method])) {
                return call_user_func([$controller, $this->method], $args);
            } else {
                throw new ActionException('Method ' . $this->method . ' not found in Controller ' . $this->class);
            }

        } else {
            throw new ActionException('Controller ' . $this->file . ' not found.');
        }
    }

    /**
     * Find and prepare controller file
     *
     * @param array $parts
     * @return \stdClass
     */
    private function prepareController(array $parts)
    {
        $supposed_method = 'index';

        while (count($parts)) {

            $supposed_class = implode('/', $parts);

            $extensions_file = ExtensionManager::findController($supposed_class . '.php');

            if ($extensions_file) {
                $file = $extensions_file;
            } else {
                $file = DIR_APPLICATION . 'controller/' . $supposed_class . '.php';
            }

            if (!file_exists($file)) {
                $supposed_method = end($parts);
                array_pop($parts);
            } else {
                break;
            }
        }

        $object = new \stdClass();
        $object->file = $file;
        $object->supposed_class = $supposed_class;
        $object->supposed_method = $supposed_method;
        return $object;
    }
}