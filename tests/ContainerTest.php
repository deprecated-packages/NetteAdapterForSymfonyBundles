<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\CommandBus;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerSource\AutowiredService;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerSource\EntityManager;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerSource\SomeService;

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

        $entityManager = $this->container->getByType(EntityManagerInterface::class);
        $this->assertInstanceOf(EntityManager::class, $entityManager);
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
