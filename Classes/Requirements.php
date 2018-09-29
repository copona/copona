<?php

namespace Copona\Classes;

class Requirements
{
    /**
     * Get all settings PHP
     *
     * @return array
     */
    public static function getSettingsPHP()
    {
        return [
            'PHP Version'        => Requirements::phpVersion(),
            'Register Globals'   => Requirements::registerGlobas(),
            'Magic Quotes GPC'   => Requirements::magicQuotesGpc(),
            'File Uploads'       => Requirements::fileUploads(),
            'Session Auto Start' => Requirements::sessionAutoStart(),
        ];
    }

    /**
     * Get all extensions PHp required
     *
     * @return array
     */
    public static function getSettingsExtension()
    {
        return [
            'Database' => Requirements::db(),
            'GD'       => Requirements::extension('gd'),
            'cURL'     => Requirements::extension('curl'),
            'DOM'      => Requirements::extension('dom'),
            'OpenSSL'  => Requirements::extension('openssl'),
            'XML'      => Requirements::extension('xml'),
            'ZLIB'     => Requirements::extension('zlib'),
            'ZIP'      => Requirements::extension('zip'),
            'mbstring' => Requirements::extension('mbstring'),
            'iconv'    => Requirements::iconv(),
        ];
    }

    public static function phpVersion()
    {
        return [
            'current'  => phpversion(),
            'required' => '7.1+',
            'status'   => (version_compare(phpversion(), '7.1.0') >= 0),
        ];
    }

    public static function registerGlobas()
    {
        return [
            'current'  => (boolean)ini_get('register_globals'),
            'required' => false,
            'status'   => (boolean)ini_get('register_globals')
        ];
    }

    public static function magicQuotesGpc()
    {
        return [
            'current'  => (boolean)ini_get('magic_quotes_gpc'),
            'required' => false,
            'status'   => (boolean)ini_get('magic_quotes_gpc')
        ];
    }

    public static function fileUploads()
    {
        return [
            'current'  => (boolean)ini_get('file_uploads'),
            'required' => true,
            'status'   => (boolean)ini_get('file_uploads')
        ];
    }

    public static function sessionAutoStart()
    {
        return [
            'current'  => (boolean)ini_get('session_auto_start'),
            'required' => false,
            'status'   => (boolean)ini_get('session_auto_start')
        ];
    }

    public static function iconv()
    {
        return [
            'current'  => function_exists('iconv'),
            'required' => true,
            'status'   => function_exists('iconv')
        ];
    }

    public static function db()
    {
        $db = [
            'mysqli',
            'pgsql',
            'pdo'
        ];

        if (!array_filter($db, 'extension_loaded')) {
            $status_db = false;
        } else {
            $status_db = true;
        }

        return [
            'current'  => $status_db,
            'required' => true,
            'status'   => $status_db
        ];
    }

    /**
     * Get and check writable paths
     *
     * @return array
     */
    public static function paths()
    {
        return [
            'config_env'    => [
                'path'   => DIR_PUBLIC . '/.env',
                'exists' => (is_file(DIR_PUBLIC . '/.env') && is_writable(DIR_PUBLIC . '/.env'))
            ],
            'image'         => [
                'path'   => DIR_PUBLIC . '/image',
                'exists' => (is_writable(DIR_PUBLIC . '/image'))
            ],
            'image_catalog' => [
                'path'   => DIR_PUBLIC . '/image/catalog',
                'exists' => (is_writable(DIR_PUBLIC . '/image/catalog')),
            ],
            'image_cache'   => [
                'path'   => DIR_CACHE_PUBLIC . 'image',
                'exists' => (is_writable(DIR_CACHE_PUBLIC . 'image')),
            ],
            'cache_public'  => [
                'path'   => DIR_CACHE_PUBLIC,
                'exists' => (is_writable(DIR_CACHE_PUBLIC)),
            ],
            'cache_private' => [
                'path'   => DIR_CACHE_PRIVATE,
                'exists' => (is_writable(DIR_CACHE_PRIVATE)),
            ],
            'logs'          => [
                'path'   => DIR_LOGS,
                'exists' => (is_writable(DIR_LOGS)),
            ],
            'download'      => [
                'path'   => DIR_DOWNLOAD,
                'exists' => (is_writable(DIR_DOWNLOAD)),
            ],
            'upload'        => [
                'path'   => DIR_UPLOAD,
                'exists' => (is_writable(DIR_UPLOAD)),
            ],
            'modification'  => [
                'path'   => DIR_MODIFICATION,
                'exists' => (is_writable(DIR_MODIFICATION)),
            ],
        ];
    }

    /**
     * Check php extension
     *
     * @param $name
     * @param bool $required
     * @return array
     */
    public static function extension($name, $required = true)
    {
        return [
            'current'  => (boolean)extension_loaded($name),
            'required' => $required,
            'status'   => (boolean)extension_loaded($name)
        ];
    }
}
