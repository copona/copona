<?php

namespace Copona\System\Engine;

use Registry;
use Copona\Exception\ActionException;
use Copona\System\Library\Extension\ExtensionManager;

/**
 * Class Action
 *
 * @package Copona\System\Engine
 * @author Mykhailo YATSYSHYN <mail@maykl-yatsyshyn.info>
 */
class Action
{
    /**
     * Default called method
     */
    const DEFAULT_METHOD = 'index';

    /**
     * Action object
     *
     * @var \stdClass
     */
    private $action;

    /**
     * Action constructor.
     *
     * @param string $route
     */
    public function __construct(string $route)
    {
        $this->prepareController($route);
    }

    /**
     * Execute action
     *
     * @param Registry $registry
     * @param mixed $args
     *
     * @return bool|mixed
     *
     * @throws ActionException
     */
    public function execute(Registry $registry, &$args = [])
    {
        // Stop any magical methods being called
        if (substr($this->action->method, 0, 2) == '__') {
            return false;
        }

        // Check is exists file
        if (!is_file($this->action->file)) {
            throw new ActionException('Controller ' . $this->action->file . ' not found.');
        }

        include_once($this->action->file);

        $controller = new $this->action->class($registry);
        // Check is callable class
        if (!is_callable([$controller, $this->action->method])) {
            throw new ActionException('Method ' . $this->action->method . ' not found in Controller ' . $this->action->class);
        }

        return call_user_func([$controller, $this->action->method], $args);
    }

    /**
     * Find and prepare controller file
     *
     * @param string $route
     */
    private function prepareController(string $route)
    {
        // Create action object
        $action = new \stdClass();
        $action->method = self::DEFAULT_METHOD;

        // Parse route params
        $parts = explode('/', preg_replace(
            '/[^a-zA-Z0-9_\/]/', '', $route
        ));

        while (count($parts)) {
            $class_name = implode('/', $parts);

            // Create class name
            $action->class = 'Controller' . preg_replace(
                    '/[^a-zA-Z0-9]/', '', $class_name
                );

            // Search controller file
            $controller_file = ExtensionManager::findController($class_name . '.php');
            $action->file = (bool)$controller_file
                ? $controller_file
                : DIR_APPLICATION . 'controller/' . $class_name . '.php';

            // Select file and set method
            if (!file_exists($action->file)) {
                $action->method = end($parts);
                array_pop($parts);
            } else {
                break;
            }
        }

        $this->action = $action;
    }
}