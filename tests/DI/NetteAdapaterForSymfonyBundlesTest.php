<?php

namespace Symplify\NetteAdapaterForSymfonyBundles\Tests;

use Nette\DI\Compiler;
use Nette\DI\Config\Loader;
use Nette\DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Symplify\NetteAdapaterForSymfonyBundles\DI\NetteAdapaterForSymfonyBundlesExtension;

final class NetteAdapaterForSymfonyBundlesTest extends TestCase
{
    /**
     * @var NetteAdapaterForSymfonyBundlesExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->extension = new NetteAdapaterForSymfonyBundlesExtension();
        $compiler = new Compiler(new ContainerBuilder());
        $this->extension->setCompiler($compiler, 'symfonyBundles');

        // simulates required Nette\Configurator default parameters
        $compiler->addConfig([
            'parameters' => [
                'appDir' => '',
                'tempDir' => ContainerFactory::createAndReturnTempDir(),
                'debugMode' => true,
                'productionMode' => true,
                'environment' => '',
            ],
        ]);
    }

    public function testLoadBundlesEmpty()
    {
        $bundles = (new Loader())->load(__DIR__.'/NetteAdapaterForSymfonyBundlesSource/bundles.neon');
        $this->extension->setConfig($bundles);
        $this->extension->loadConfiguration();
        $this->extension->beforeCompile();

        $builder = $this->extension->getContainerBuilder();
        $this->assertGreaterThan(17, $builder->getDefinitions());
    }
}
