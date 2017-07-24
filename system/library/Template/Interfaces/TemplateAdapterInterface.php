<?php

namespace Copona\System\Library\Template\Interfaces;

interface TemplateAdapterInterface
{
    /**
     * Render and return the template with the data
     *
     * @param string $template_file
     * @param array $data
     * @return mixed
     */
    public function render($template_file, Array $data);

    /**
     * Return all extensions file what the is supported by adapter
     *
     * @return array
     */
    public function getExtensionsSupport();
}