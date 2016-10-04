<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Transformer\DI;

use Nette\DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;
use Symnedi\SymfonyBundlesExtension\Transformer\DI\TransformerFactory;

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
