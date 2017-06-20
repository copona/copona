<?php
class Action
{
    private $id;
    private $route;
    private $method = 'index';
    public $extension_file;

    public function __construct($route)
    {
        $this->id = $route;

        $parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route));

        $extensions_dir = preg_replace('/\/[a-z]*\/$/', '', DIR_SYSTEM);

        // Break apart the route
        while ($parts) {

            // TODO: optimize this! Probably - with a new variable. Also needed in Extension controller in admin.
            APPLICATION == 'admin'
              ? $extension_files = glob($extensions_dir . "/extensions/*/*/admin/controller/" . implode('/',
                $parts) . ".php")
              : $extension_files = glob($extensions_dir . "/extensions/*/*/catalog/controller/" . implode('/',
                $parts) . ".php");

            $file = DIR_APPLICATION . 'controller/' . implode('/', $parts) . '.php';

            if (is_file($file) || !empty($extension_files[0])) {
                if (!empty($extension_files[0])) {
                    $this->extension_file = $extension_files[0];
                }
                $this->route = implode('/', $parts);
                break;
            } else {
                $this->method = array_pop($parts);
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function execute($registry, array $args = array())
    {
        // Stop any magical methods being called
        if (substr($this->method, 0, 2) == '__') {
            return new \Exception('Error: Calls to magic methods are not allowed!');
        }

        if (!empty($this->extension_file)) {
            $file = $this->extension_file;
        } else {
            $file = DIR_APPLICATION . 'controller/' . $this->route . '.php';
        }
        $class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $this->route);

        // Initialize the class
        if (is_file($file)) {
            include_once($file);

            $controller = new $class($registry);
        } else {
            return new \Exception('Error: Could not call ' . $this->route . '/' . $this->method . '!');
        }

        $reflection = new ReflectionClass($class);

        if ($reflection->hasMethod($this->method) && $reflection->getMethod($this->method)->getNumberOfRequiredParameters() <= count($args)) {
            return call_user_func_array(array($controller, $this->method), $args);
        } else {
            return new \Exception('Error: Could not call ' . $this->route . '/' . $this->method . '!');
        }
    }

}