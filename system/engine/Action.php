<?php

namespace Copona\System\Engine;

use Copona\System\Library\Extension\ExtensionManager;
use Symfony\Component\Yaml\Exception\RuntimeException;

class Action
{
    private $id;
    private $route;
    private $method = 'index';
    public $extension_file;

    public function __construct($route)
    {
        $this->id = $route;

        $info_file = $this->prepareController($route, 2);

        if (is_file($info_file->file)) {
            $this->file = $info_file->file;
            $this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $info_file->supposed_class);
        } else {
            $info_file = $this->prepareController($route, 3);
            $this->file = $info_file->file;
            $this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $info_file->supposed_class);
        }

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
                throw new RuntimeException('Method ' . $this->method . ' not found in Controller ' . $this->class);
            }

        } else {
            throw new RuntimeException('Controller ' . $this->file . ' not found.');
        }
    }

    /**
     * Find and prepare controller file
     *
     * @param $route
     * @param int $part_count
     * @return \stdClass
     */
    private function prepareController($route, $part_count = 2)
    {
        // Break apart the route
        $parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route));

        if (is_array($parts) && count($parts) > $part_count) {
            $aux_parts = $parts;
            $supposed_method = end($aux_parts);
            array_pop($aux_parts);
            $supposed_class = implode('/', $aux_parts);
        } else {
            $supposed_class = implode('/', $parts);
            $supposed_method = 'index';
        }

        $extensions_file = ExtensionManager::findController($supposed_class . '.php');

        if ($extensions_file) {
            $file = $extensions_file;
        } else {
            $file = DIR_APPLICATION . 'controller/' . $supposed_class . '.php';
        }

        $object = new \stdClass();
        $object->file = $file;
        $object->supposed_class = $supposed_class;
        $object->supposed_method = $supposed_method;
        return $object;
    }
}