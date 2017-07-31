<?php

namespace Copona\System\Library\Template;

use Copona\System\Library\Template\Interfaces\TemplateAdapterInterface;

class TemplateFactory
{
    /**
     * Factory create instance adaptor template parse
     *
     * @param $adaptor
     * @return TemplateAdapterInterface
     * @throws \RuntimeException
     */
    public static function create($adaptor)
    {
        if (class_exists($adaptor)) {
            $adaptorIntance = new $adaptor();

            if ($adaptorIntance instanceof TemplateAdapterInterface) {
                return $adaptorIntance;
            } else {
                throw new \RuntimeException('Adaptor ' . $adaptor . ' is not instance of ' . TemplateAdapterInterface::class);
            }

        } else {
            throw new \RuntimeException('Error: Could not load template adaptor ' . $adaptor . '!');
        }
    }
}