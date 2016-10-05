<?php

namespace Symplify\SymfonyBundlesExtension\Tests\TacticianBundle;

use League\Tactician\CommandBus;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;
use Symplify\SymfonyBundlesExtension\Tests\ContainerFactory;
use Symplify\SymfonyBundlesExtension\Tests\TacticianBundle\NetteTagsSource\SomeCommand;

final class NetteTagsTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function __construct()
    {
        $this->container = (new ContainerFactory())->createWithConfig(__DIR__.'/config/netteTags.neon');
    }

    public function testTags()
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->container->getByType(CommandBus::class);
        $this->assertInstanceOf(CommandBus::class, $commandBus);

        $someCommand = new SomeCommand();
        $this->assertNull($someCommand->getState());

        $commandBus->handle($someCommand);

        $this->assertSame('changedState', $someCommand->getState());
    }
}
