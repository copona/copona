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
        $extension->namespace = $extensionPath->getRelativePathname();
        $extension->name = $extensionPath->getFilename();
        $extension->vendor = $extensionPath->getRelativePath();
        $extension->path = $extensionPath;

        $extension_finder = $finder
          ->in($extensionPath->getPathname())

          ->ignoreVCS(true)
          ->ignoreDotFiles(true)
          ->ignoreUnreadableDirs()

          ->notPath('assets')
          ->notPath('javascript')
          ->notPath('image')
          ->notPath('stylesheet')

          ->notName('(?<!\.min)\.(js|css)$') // ignore js and css

          ->notPath('#(^|/)_.+(/|$)#') // Ignore path start with underscore (_).

          ->depth('< 0')
          ->files();


        foreach ($extension_finder as $item) {
            $extension->files[] = $item->getPathname();
        }

        $this->push($extension);
    }
}