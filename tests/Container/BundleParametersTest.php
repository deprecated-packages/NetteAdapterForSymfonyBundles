<?php

namespace Symplify\NetteAdapaterForSymfonyBundles\Tests\Container;

use Nette\DI\Container;
use PHPUnit\Framework\TestCase;
use Symplify\NetteAdapaterForSymfonyBundles\DI\NetteAdapaterForSymfonyBundlesExtension;
use Symplify\NetteAdapaterForSymfonyBundles\SymfonyContainerAdapter;
use Symplify\NetteAdapaterForSymfonyBundles\Tests\ContainerFactory;

final class BundleParametersTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function __construct()
    {
        $this->container = (new ContainerFactory())->createWithConfig(
            __DIR__.'/SymfonyContainerAdapterSource/config/init.neon'
        );
    }

    public function testBundleParameters()
    {
        $symfonyContainerAdapter = $this->getSymfonyContainerAdapter();
        $this->assertTrue($symfonyContainerAdapter->hasParameter('doctrine.orm.proxy_namespace'));
    }

    private function getSymfonyContainerAdapter() : SymfonyContainerAdapter
    {
        return $this->container->getService(
            NetteAdapaterForSymfonyBundlesExtension::SYMFONY_CONTAINER_SERVICE_NAME
        );
    }
}
