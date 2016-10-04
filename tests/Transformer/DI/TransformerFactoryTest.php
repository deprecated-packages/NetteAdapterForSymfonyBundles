<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Transformer\DI;

use Nette\DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;
use Symnedi\SymfonyBundlesExtension\Transformer\DI\TransformerFactory;


final class TransformerFactoryTest extends TestCase
{

	public function testWithApplicationExtension()
	{
		$transformerFactory = new TransformerFactory(new ContainerBuilder, TEMP_DIR);

		$transformer = $transformerFactory->create();
		$containerBuilderTransformer = $transformer->getByType(ContainerBuilderTransformer::class);
		$this->assertInstanceOf(ContainerBuilderTransformer::class, $containerBuilderTransformer);
	}

}
