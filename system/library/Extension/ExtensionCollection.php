<?php

namespace Copona\System\Library\Extension;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ExtensionCollection extends Collection
{
    public function add(SplFileInfo $extensionPath)
    {
        $finder = new Finder();

        $extension = new ExtensionItem();
        $extension->setName($extensionPath->getFilename());
        $extension->setVendor($extensionPath->getRelativePath());
        $extension->setNamespace('Extension\\' . $extension->getVendor() . '\\' . $extension->getName());
        $extension->setPath($extensionPath);

        $extension_finder = $finder
            ->in($extensionPath->getPathname())
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->ignoreUnreadableDirs()
            ->notPath('assets')
            ->notPath('javascript')
            ->notPath('image')
            ->notPath('stylesheet')
            ->notPath('vendor')
            ->notName('(?<!\.min)\.(js|css)$')// ignore js and css

            ->notPath('#(^|/)_.+(/|$)#')// Ignore path start with underscore (_).

            ->depth('< 0')
            ->files();


        foreach ($extension_finder as $item) {
            $extension->setFile($item->getPathname());
        }

        $this->push($extension);
    }
}