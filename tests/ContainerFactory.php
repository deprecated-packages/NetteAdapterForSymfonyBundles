<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Nette\Configurator;
use Nette\DI\Container;

final class ContainerFactory
{
    public function create() : Container
    {
        return $this->createWithConfig(__DIR__.'/config/default.neon');
    }

    public function createWithConfig(string $config) : Container
    {
        $configurator = new Configurator();
        $configurator->addConfig($config);
        $configurator->setTempDirectory(TEMP_DIR);

        return $configurator->createContainer();
    }
}
