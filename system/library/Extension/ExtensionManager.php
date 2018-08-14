<?php

namespace Copona\System\Library\Extension;

use Copona\Cache\CacheManager;
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

    /**
     * @var Finder
     */
    protected static $finder;

    public function __construct()
    {
        /** @var CacheManager $cache */
        $cache = \Registry::getInstance()->get('cache');

        if ($cache->has('extensionCollection')) {
            self::$extensionCollection = $cache->get('extensionCollection');
        } else {

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

            $cache->set('extensionCollection', self::$extensionCollection);

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

    /**
     * Get collection extension
     *
     * @return ExtensionCollection
     */
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
     * @throws \Exception
     */
    public static function getCronjobRegisted($namespace)
    {
        return self::getInstanceClass($namespace)->registerCronjob();
    }

    /**
     * Get all cronjob registed inside extensions
     *
     * @return array
     * @throws \Exception
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
     * Execute all event onInit in all site
     *
     * @return array
     * @throws \Exception
     */
    public static function executeOnInit()
    {
        /** @var ExtensionItem $extensionItem */
        foreach (self::$extensionCollection->all() as $extensionItem) {
            $extensionItem->getIntance()->onInit();
        }
    }

    /**
     * blame @arnisjuraga :)
     * Execute all init methods for Catalog
     *
     * @throws \Exception
     */
    public static function initAllCatalog()
    {
        /** @var ExtensionItem $extensionItem */
        foreach (self::$extensionCollection->all() as $extensionItem) {
            $extensionItem->getIntance()->initCatalog();
        }
    }

    /**
     * Execute all update method
     *
     * @throws \Exception
     */
    public static function executeAllUpdate()
    {
        /** @var ExtensionItem $extensionItem */
        foreach (self::$extensionCollection->all() as $extensionItem) {
            $extensionItem->getIntance()->update();
        }
    }

    /**
     * Execute all uninstall method
     *
     * @throws \Exception
     */
    public static function executeAllUninstall()
    {
        /** @var ExtensionItem $extensionItem */
        foreach (self::$extensionCollection->all() as $extensionItem) {
            $extensionItem->getIntance()->uninstall();
        }
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

        foreach ($paths as $path) {

            $view_name = preg_quote($path, '/');

            $extensions_file = self::findFile($view_name);

            if ($extensions_file && count($extensions_file)) {
                return reset($extensions_file);
            }
        }
    }

    /**
     * Find by controller
     *
     * @param $controller_path
     * @return mixed
     */
    public static function findController($controller_path)
    {
        $controller = preg_quote(APPLICATION . '/' . 'controller/' . $controller_path, '/');

        $extensions_file = self::findFile($controller);

        if ($extensions_file && count($extensions_file)) {
            return reset($extensions_file);
        }
    }

    /**
     * Find by model
     *
     * @param $model_path
     * @return mixed
     */
    public static function findModel($model_path)
    {
        $model = preg_quote(APPLICATION . '/' . 'model/' . $model_path, '/');

        $extensions_file = self::findFile($model . "\.php");

        if ($extensions_file && count($extensions_file)) {
            return reset($extensions_file);
        }
    }

    /**
     * Find Language
     *
     * @param $language_path
     * @return mixed
     */
    public static function findLanguage($language_path)
    {
        $language = preg_quote(APPLICATION . '/' . 'language/' . $language_path, '/');

        $extensions_file = self::findFile($language);

        if ($extensions_file && count($extensions_file)) {
            return reset($extensions_file);
        }
    }

    /**
     * Find by file in extensions
     *
     * @param $query
     * @return array
     */
    public static function findFile($query)
    {
        $filesCollection = self::$extensionCollection->pluck('files')->toArray();
        $filesInterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($filesCollection));
        $files = iterator_to_array($filesInterator, false);

        return preg_grep("/\b$query\b/i", $files);
    }
}