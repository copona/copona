<?php

namespace Copona\System\Library;

use Registry;

/**
 * Class Breadcrumbs
 *
 * @package Copona\System\Library
 * @author wazzzar
 * @author Mykhailo YATSYSHYN <mail@maykl-yatsyshyn.info>
 */
class Breadcrumbs
{
    /**
     * @var \Url
     */
    protected $url;

    /**
     * @var \Language
     */
    protected $language;

    /**
     * List breadcrumbs item
     *
     * @var array
     */
    private $path = [];

    /**
     * Breadcrumbs constructor.
     *
     * @param \Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->url = $registry->get('url');
        $this->language = $registry->get('language');

        $this->push('text_home', 'common/home');
    }

    /**
     * Add item breadcrumbs
     *
     * @param string $text
     * @param string $route
     * @param string $args
     * @param bool $secure
     */
    public function push(
        string $text,
        string $route,
        string $args = "",
        bool $secure = false
    ) {
        $this->path[] = [
            'text' => $this->language->get($text),
            'href' => $this->url->link($route, $args, $secure),
        ];
    }

    /**
     * Render breadcrumbs
     *
     * @return null|string
     */
    public function render()
    {
        // if in path only home link
        if (count($this->path) == 1) {
            return null;
        }

        $html = '<ul class="breadcrumb">';
        foreach ($this->path as $part) {
            $html .= '<li><a href="' . $part['href'] . '">' . $part['text'] . '</a></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Get breadcrumb path
     *
     * @return array|null
     */
    public function getPath()
    {
        // if in path only home link
        if (count($this->path) == 1) {
            return null;
        }

        return $this->path;
    }
}
