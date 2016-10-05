<?php

namespace Symplify\SymfonyBundlesExtension\Tests\Transformer\DI;

use Nette\DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Symplify\SymfonyBundlesExtension\Tests\ContainerFactory;
use Symplify\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;
use Symplify\SymfonyBundlesExtension\Transformer\DI\TransformerFactory;

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
