<?php

class Url
{
    private $url;

    private $ssl;

    private $rewrite = array();

    private $code = '';

    private $url_parts = [
        'filter',
        'manufacturer_id',
        'sort',
        'order',
        'page',
        'limit',
        'path',
        'route'
    ];

    public function __construct($url, $ssl = '', $registry)
    {

        $this->config = $registry->get('config');
        $this->session = $registry->get('session');
        $this->request = $registry->get('request');

        $this->url = $url;
        $this->ssl = $ssl;

        $this->code = ($this->config->get('config_seo_url') && APPLICATION == 'catalog' ? $this->session->data['language'] : '');
        Config::set('code', $this->code);
    }

    public function addRewrite($rewrite)
    {
        $this->rewrite[] = $rewrite;
    }

    public function link($route, $args = '', $secure = false)
    {
        $code = $this->code ? $this->code . "/" : '';
        if ($_SERVER['HTTPS'] == true) {
            $url = $this->ssl . $code . 'index.php?route=' . $route;
        } else {
            $url = $this->url . $code . 'index.php?route=' . $route;
        }

        if ($args) {
            if (is_array($args)) {
                $url .= '&amp;' . http_build_query($args);
            } else {
                $url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
            }
        }

        foreach ($this->rewrite as $rewrite) {
            $url = $rewrite->rewrite($url);
        }

        return $url;
    }

    public function externalLink($link = '')
    {
        if ($link) {
            $external_link = parse_url($link);
            if (isset($external_link['scheme'])) {
                $ex_link = $link;
            } elseif (!isset($external_link['scheme']) && isset($external_link['path'])) {
                $ex_link = $this->link($link);
            }
        } else {
            $ex_link = '';
        }

        return $ex_link;
    }

    /**
     * We need some methods for urls:
     * 1. return partly built URL from CURRENT get params in format key=val&key1=val1...
     * 2. return ARRAY of needed keys from current get url, to be able to override them
     * 3. additional: pass all parameters in once, and build url
     * 4. custom get params also loaded on exec.
     */
    public function getParams()
    {
        $result = [];

        foreach( $this->request->get as $key => $val ) {
            array_push($this->url_parts, $key);
        }

        $this->url_parts = array_unique($this->url_parts);

        foreach ($this->url_parts as $key) {
            $result[$key] = isset($this->request->get[$key]) ? $this->request->get[$key] : '';
        }

        return $result;
    }

    public function getPartly($data, $string = false)
    {
        // $this->url_parts
        $result = [];
        foreach ($data as $key) {
            if (isset($this->request->get[$key])) {
                $result[$key] = $this->request->get[$key];
            }
        }

        if ($string) {
            return http_build_query($result);
        } else {
            return $result;
        }
    }

    public function setRequest($data, $string = true)
    {
        $result = [];
        foreach ($data as $key => $val) {
            $result[$key] = $val;
        }

        if ($string) {
            return http_build_query($result);
        } else {
            return $result;
        }
    }

    /**
     * Make url image
     *
     * @param string $image
     * @return string
     */
    public function getImageUrl($image)
    {
        if ($this->request->server['HTTPS']) {
            return 'https://' . rtrim($this->config->get('image_base_url', $this->config->get('site_base')), '/') . '/' . $image;
        } else {
            return 'http://' . rtrim($this->config->get('image_base_url', $this->config->get('site_ssl')), '/') . '/' . $image;
        }
    }

    /**
     * Make original, full size image URL
     *
     * @param string $image
     * @return string
     */
    public function getImageUrlOriginal($image)
    {
        if ($this->request->server['HTTPS']) {
            return 'https://' . BASE_URL_IMAGE . '/' . $image;
        } else {
            return 'http://' . BASE_URL_IMAGE . '/' . $image;
        }
    }
}