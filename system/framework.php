<?php
// Registry
$registry = Registry::getInstance();

// Register Config
global $config;
$registry->set('config', $config);

//Extension
use \Copona\System\Library\Extension\ExtensionManager;
$extension = ExtensionManager::getInstance();
$registry->set('extension', $extension);

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has($application_config . '.action_event')) {
    foreach ($config->get($application_config . '.action_event') as $key => $value) {
        $event->register($key, new \Copona\System\Engine\Action($value));
    }
}

// Hook
$registry->singleton('hook', function ($registry) {
    return new Hook($registry);
});

// Loader
use \Copona\System\Engine\Loader;
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$registry->singleton('request', Request::class);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);
$GLOBALS['response'] = $response;

// Database
if ($config->get($application_config . '.db_autostart')) {

    //default connection
    $default_connection = $config->get('database.default_connection') ? $config->get('database.default_connection') : 'default';
    $db_config = $config->get('database.' . $default_connection);
    define('DB_PREFIX', $db_config['db_prefix']);

    $registry->singleton('db', function ($registry) use ($db_config) {
        return new DB(
            $db_config['db_type'],
            $db_config['db_hostname'],
            $db_config['db_username'],
            $db_config['db_password'],
            $db_config['db_database'],
            $db_config['db_port']
        );
    });
}

// Session
$registry->singleton('session', function ($registry) {
    $session = new Session();
    $session->start();
    return $session;
});

// Cache
$registry->singleton('cache', function ($registry) use ($config) {
    return new Cache($config->get('cache.cache_type'), $config->get('cache.cache_expire'));
});

// Url
$registry->singleton('url', function ($registry) use ($config) {
    return new Url($config->get('site_base'), $config->get('site_ssl'), $registry);
});

// Copona seo urls
$registry->singleton('seourl', function ($registry) {
    return new seoUrl($registry);
});

// Language
$registry->singleton('language', function ($registry) use ($config) {
    $language = new Language($config->get('language_default'), $registry);
    $language->load($config->get('language_default'));
    return $language;
});

// Breadcrumbs
$registry->bind('breadcrumbs', function ($registry) {
    return new Breadcrumbs($registry);
});

// Document
$registry->singleton('document', Document::class);

// Config Autoload
if ($config->has('config_autoload')) {
    foreach ($config->get('config_autoload') as $value) {
        $loader->config($value);
    }
}

// Language Autoload
if ($config->has('language_autoload')) {
    foreach ($config->get('language_autoload') as $value) {
        $loader->language($value);
    }
}

// Library Autoload
if ($config->has('library_autoload')) {
    foreach ($config->get('library_autoload') as $value) {
        $loader->library($value);
    }
}

// Model Autoload
if ($config->has('model_autoload')) {
    foreach ($config->get('model_autoload') as $value) {
        $loader->model($value);
    }
}

// Front Controller
$controller = new \Copona\System\engine\Front($registry);

// Pre Actions
if ($config->has($application_config . '.action_pre_action')) {
    foreach ($config->get($application_config . '.action_pre_action') as $value) {
        $controller->addPreAction(new \Copona\System\Engine\Action($value));
    }
}
// Dispatch
$controller->dispatch(
    new \Copona\System\Engine\Action($config->get($application_config . '.action_router')),
    new \Copona\System\Engine\Action($config->get($application_config . '.action_error'))
);
