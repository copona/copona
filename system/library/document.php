<?php

class Document
{
    private $title;
    private $description;
    private $keywords;
    private $links;
    private $styles;
    private $scripts;
    private $route;
    private $theme_name;
    private $request;
    private $ogmetas = [];
    private $json_ld = [];

    public function __construct()
    {
        $registry = Registry::getInstance();
        $this->request = $registry->get('request');
        $this->scripts = ['common' => ['header' => [], 'footer' => []]];
        $this->links = $this->styles = ['common' => []];
        $this->theme_name = Config::get('theme_name');
    }

    private function checkHref(&$href)
    {
        if (!filter_var($href, FILTER_VALIDATE_URL) && strpos($href, '//') !== 0) {
            if (file_exists('themes/' . $this->theme_name . '/' . $href)) {
                $href = 'themes/' . $this->theme_name . '/' . $href;
            } elseif (file_exists('themes/default/' . $href)) {
                $href = 'themes/default/' . $href;
            } else {
                // dont't change. Developer responsible, so that the file exists on server.
                // it can be "dynamic" link anyway.
            }
        }
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function addLink($href, $rel, $route = '')
    {
        if (!$route) $route = 'common';

        $this->checkHref($href);

        $this->links[$route][$href] = array(
            'href' => $href,
            'rel' => $rel
        );
    }

    public function getLinks()
    {
        $result = [];

        $this->route = !empty($this->request->get['route']) ? $this->request->get['route'] : '';

        if (!empty($this->links[$this->route]) && $this->links != 'common') {
            return array_merge($result, $this->links['common'], $this->links[$this->route]);
        } else {
            return $this->links['common'];
        }
    }

    public function addStyle($href, $rel = 'stylesheet', $media = 'screen', $route = '')
    {
        if (!$route) $route = 'common';

        $this->checkHref($href);

        $this->styles[$route][$href] = array(
            'href' => $href,
            'rel' => $rel,
            'media' => $media
        );
    }

    public function removeStyle($href, $rel = 'stylesheet', $media = 'screen', $route = '')
    {
        if (!$route) $route = 'common';

        unset($this->styles[$route][$href]);
    }
    
    public function addStyleVersioned($href, $rel = 'stylesheet', $media = 'screen', $route = '')
    {

        if (!$route) $route = 'common';

        $hash = md5(date('dmY'));
        if (file_exists(ltrim($href, '/'))) {
            $hash = md5_file(ltrim($href, '/'));
        }
        $hash = substr($hash, 0, 10);
        if (strpos($href, '?') == false) {
            $href .= "?v=" . $hash;
        } else {
            $href .= "&v=" . $hash;
        }
        $this->addStyle($href, $rel, $media, $route);
    }

    public function getStyles()
    {
        $result = [];
        $this->route = !empty($this->request->get['route']) ? $this->request->get['route'] : '';
        if (!empty($this->styles[$this->route]) && $this->route != 'common') {
            return array_merge($result, $this->styles['common'], $this->styles[$this->route]);
        } else {
            return $this->styles['common'];
        }
        // return $this->styles;
    }

    public function addScriptVersioned($href, $position = 'header', $route = '')
    {
        $hash = md5(date('dmY'));
        if (file_exists(ltrim($href, '/'))) {
            $hash = md5_file(ltrim($href, '/'));
        }
        $hash = substr($hash, 0, 10);
        if (strpos($href, '?') == false) {
            $href .= "?v=" . $hash;
        } else {
            $href .= "&v=" . $hash;
        }
        $this->addScript($href, $position);
    }

    public function addScript($href, $position = 'header', $route = '')
    {
        if (!$route) $route = 'common';

        $this->checkHref($href);

        $this->scripts[$route][$position][$href] = $href;
    }

    public function removeScript($href, $position = 'header', $route = '')
    {
        if (!$route) $route = 'common';

        unset($this->scripts[$route][$position][$href]);
    }


    public function getScripts($position = 'header')
    {
        $result = [];
        $this->route = !empty($this->request->get['route']) ? $this->request->get['route'] : '';

        // prd($this->scripts);

        if (!empty($this->scripts[$this->route][$position]) && $this->route != 'common') {
            return array_merge($result, $this->scripts['common'][$position], $this->scripts[$this->route][$position]);
        } else {
            return $this->scripts['common'][$position];
        }
    }

    public function addOGMeta($name, $content) {
        $this->ogmetas[] = array(
            'name'  => $name,
            'content'   => $content
        );
    }
    public function getOGMeta() {
        return $this->ogmetas;
    }

    /**
     * Add a schema.org structured-data object, rendered in <head> as
     * <script type="application/ld+json">.
     */
    public function addJsonLd(array $schema) {
        if (!isset($schema['@context'])) {
            $schema = ['@context' => 'https://schema.org'] + $schema;
        }

        $this->json_ld[] = $schema;
    }

    /**
     * Add a schema.org BreadcrumbList from breadcrumbs in the usual
     * [['text' => ..., 'href' => ...], ...] shape.
     */
    public function addBreadcrumbJsonLd(array $breadcrumbs) {
        $list = [];

        foreach (array_values($breadcrumbs) as $i => $crumb) {
            $list[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => trim(strip_tags(html_entity_decode((string)$crumb['text'], ENT_QUOTES, 'UTF-8'))),
                'item'     => html_entity_decode((string)$crumb['href'], ENT_QUOTES, 'UTF-8'),
            ];
        }

        if ($list) {
            $this->addJsonLd(['@type' => 'BreadcrumbList', 'itemListElement' => $list]);
        }
    }

    /**
     * @return string[] JSON strings, safe to print inside a <script> element.
     */
    public function getJsonLd() {
        return array_map(function ($schema) {
            return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        }, $this->json_ld);
    }

}