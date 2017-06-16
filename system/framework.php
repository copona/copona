<?php
// Registry
$registry = Registry::getInstance();

// Register Config
global $config;
$registry->set('config', $config);

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has($application_config . '.action_event')) {
    foreach ($config->get($application_config . '.action_event') as $key => $value) {
        $event->register($key, new Action($value));
    }
}

// Hook
$hook = new Hook($registry);
$registry->set('hook', $hook);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Database
if ($config->get($application_config . '.db_autostart')) {

    //default connection
    $default_connection = $config->get('database.default_connection') ? $config->get('database.default_connection') : 'default';
    $db_config = $config->get('database.' . $default_connection);
    define('DB_PREFIX', $db_config['db_prefix']);

    $registry->set('db', new DB(
            $db_config['db_type'],
            $db_config['db_hostname'],
            $db_config['db_username'],
            $db_config['db_password'],
            $db_config['db_database'],
            $db_config['db_port'])
    );

    if (!$registry->get('db')->query('SHOW TABLES LIKE \'' . DB_PREFIX . 'setting\'')->rows) {
        //no table setting.
        die('Check Config file for correct Database connection!');
    }
}

// Session
$session = new Session();

if ($config->get('session.session_autostart')) {
    $session->start();
}

$registry->set('session', $session);

// Cache
$registry->set('cache', new Cache($config->get('cache.cache_type'), $config->get('cache.cache_expire')));

// Url
if ($config->get('url_autostart')) {
    $url = new Url($config->get('site_base'), $config->get('site_ssl'), $registry);
    $registry->set('url', $url);
}

// Copona seo urls
if ($config->get('url_autostart')) {
    $registry->set('seourl', new seoUrl($registry));
}

// Language
$language = new Language($config->get('language_default'), $registry);
$language->load($config->get('language_default'));
$registry->set('language', $language);

// Breadcrumbs
if ($config->get('url_autostart')) {
    $breadcrumbs = new Breadcrumbs($registry);
    $registry->set('breadcrumbs', $breadcrumbs);
}
// Document
$registry->set('document', new Document());

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
$controller = new Front($registry);

// Pre Actions
if ($config->has($application_config . '.action_pre_action')) {
    foreach ($config->get($application_config . '.action_pre_action') as $value) {
        $controller->addPreAction(new Action($value));
    }
}

// Dispatch
$controller->dispatch(new Action($config->get($application_config . '.action_router')), new Action($config->get($application_config . '.action_error')));

// Output
$response->setCompression($config->get('config_compression'));
$response->output();