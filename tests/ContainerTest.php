<?php

namespace Symplify\NetteAdapaterForSymfonyBundles\Tests;

use League\Tactician\CommandBus;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;
use Symplify\NetteAdapaterForSymfonyBundles\Tests\ContainerSource\AutowiredService;
use Symplify\NetteAdapaterForSymfonyBundles\Tests\ContainerSource\SomeService;

final class ContainerTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function __construct()
    {
        $this->container = (new ContainerFactory())->create();
    }

    public function testFetchingService()
    {
        $someService = $this->container->getByType(SomeService::class);
        $this->assertInstanceOf(SomeService::class, $someService);
    }

    public function testReferenceToOtherService()
    {
        $commandBus = $this->container->getByType(CommandBus::class);
        $this->assertInstanceOf(CommandBus::class, $commandBus);
    }

    public function testAutowiredService()
    {
        /** @var AutowiredService $autowiredService */
        $autowiredService = $this->container->getByType(AutowiredService::class);
        $this->assertInstanceOf(AutowiredService::class, $autowiredService);
        $this->assertInstanceOf(SomeService::class, $autowiredService->getSomeService());
    }
}
