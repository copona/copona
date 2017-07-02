<?php

namespace Copona\System\Library\Extension;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ExtensionManager
{
    /**
     * List Extensions Registred
     * @var array
     */
    protected static $extensions_registred = [];

    protected static $extension_dir;

    /**
     * List from archives from all extensions
     *
     * @var array
     */
    protected static $fileList = [];

    /**
     * @var ExtensionCollection
     */
    protected static $extensionCollection;

    protected static $viewCache = [];

    /**
     * @var Finder
     */
    protected static $finder;

    public function __construct()
    {
        self::$extension_dir = rtrim(\Config::get('extension.dir', DIR_PUBLIC . '/extensions/'), '/');

        self::$finder = new Finder();
        self::$finder->in(self::$extension_dir);
        $finder = self::$finder;

        $finder->depth('1')->directories();

        self::$extensionCollection = new ExtensionCollection();

        /** @var SplFileInfo $extensionPath */
        foreach ($finder as $extensionPath) {
            self::$extensionCollection->add($extensionPath);
        }
    }

    /**
     * @return ExtensionManager
     */
    public static function getInstance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public static function getCollection()
    {
        return self::$extensionCollection;
    }

    /**
     * Get extension
     *
     * @param string $namespace Namespace (Vendor/Name)
     * @return ExtensionItem
     */
    public static function getExtension($namespace)
    {
        return self::$extensionCollection->where('namespace', $namespace)->first();
    }

    /**
     * Get instance Class Extension by extension name
     *
     * @param $namespace
     * @return ExtensionBase
     * @throws \Exception
     */
    public static function getInstanceClass($namespace)
    {
        $extension = self::getExtension($namespace);
        if ($extension) {
            return $extension->getIntance();
        } else {
            throw new \Exception('Extension ' . $namespace . ' instance not found');
        }
    }

    /**
     * Get crojob registed extension
     *
     * @param $namespace
     * @return array
     */
    public static function getCronjobRegisted($namespace)
    {
        return self::getInstanceClass($namespace)->registerCronjob();
    }

    /**
     * Get all cronjob registed inside extensions
     *
     * @return array
     */
    public static function getAllCronjobRegisted()
    {
        $cronjob_registed = [];

        /** @var ExtensionItem $extensionItem */
        foreach (self::$extensionCollection->all() as $extensionItem) {
            $cronjob_registed = array_unique(array_merge($cronjob_registed,
              $extensionItem->getIntance()->registerCronjob()));
        }

        return $cronjob_registed;
    }

    /**
     * Find by View
     *
     * @param $view
     * @param array $extensions from templates (eg. tpl, twig...)
     * @return string
     */
    public static function findView($view, Array $extensions = [])
    {
        if (count($extensions)) {
            foreach ($extensions as $extension) {
                $paths[] = PATH_TEMPLATE . $view . '.' . $extension;
            }
        } else {
            $paths[] = PATH_TEMPLATE . $view;
        }

        $filesCollection = self::$extensionCollection->pluck('files')->toArray();

        foreach ($paths as $path) {

            $view_name = preg_quote($path, '/');

            $filesInterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($filesCollection));
            $files = iterator_to_array($filesInterator, false);

            $extensions_file = preg_grep("/\b$view_name\b/i", $files);

            if ($extensions_file && count($extensions_file)) {
                return reset($extensions_file);
            }
        }
    }

    /**
     * Find by controller
     *
     * @param $controller_name
     * @return mixed
     */
    public static function findController($controller_name)
    {
        $filesCollection = self::$extensionCollection->pluck('files')->toArray();

        $controller = preg_quote(APPLICATION . '/' . 'controller/' . $controller_name, '/');

        $filesInterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($filesCollection));
        $files = iterator_to_array($filesInterator, false);

        $extensions_file = preg_grep("/\b$controller\b/i", $files);

        if ($extensions_file && count($extensions_file)) {
            return reset($extensions_file);
        }
    }
}