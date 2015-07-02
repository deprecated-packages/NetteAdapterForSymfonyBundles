<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Transformer\DI;

use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;
use Symnedi\SymfonyBundlesExtension\Transformer\DI\TransformerFactory;


class TransformerFactoryTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var TransformerFactory
	 */
	private $transformerFactory;


	protected function setUp()
	{
		$this->transformerFactory = new TransformerFactory(new ContainerBuilder, TEMP_DIR);
	}


	public function testWithApplicationExtension()
	{
		$transformer = $this->transformerFactory->create();
		$containerBuilderTransformer = $transformer->getByType(ContainerBuilderTransformer::class);
		$this->assertInstanceOf(ContainerBuilderTransformer::class, $containerBuilderTransformer);
	}

}
