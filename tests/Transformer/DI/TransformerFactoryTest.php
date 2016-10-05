<?php

namespace Symplify\NetteAdapaterForSymfonyBundles\Tests\Transformer\DI;

use Nette\DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Symplify\NetteAdapaterForSymfonyBundles\Tests\ContainerFactory;
use Symplify\NetteAdapaterForSymfonyBundles\Transformer\ContainerBuilderTransformer;
use Symplify\NetteAdapaterForSymfonyBundles\Transformer\DI\TransformerFactory;

final class TransformerFactoryTest extends TestCase
{
    public function testWithApplicationExtension()
    {
        $transformerFactory = new TransformerFactory(
            new ContainerBuilder(),
            ContainerFactory::createAndReturnTempDir()
        );

        $transformer = $transformerFactory->create();
        $containerBuilderTransformer = $transformer->getByType(ContainerBuilderTransformer::class);
        $this->assertInstanceOf(ContainerBuilderTransformer::class, $containerBuilderTransformer);
    }
}
