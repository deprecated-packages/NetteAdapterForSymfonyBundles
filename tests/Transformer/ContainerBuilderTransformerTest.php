<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Transformer;

use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceDefinition;
use PHPUnit_Framework_TestCase;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symnedi\SymfonyBundlesExtension\Transformer\ArgumentsTransformer;
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;
use Symnedi\SymfonyBundlesExtension\Transformer\ServiceDefinitionTransformer;


class ContainerBuilderTransformerTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var ContainerBuilderTransformer
	 */
	private $containerBuilderTransformer;


	protected function setUp()
	{
		$serviceDefinitionTransformer = new ServiceDefinitionTransformer(new ArgumentsTransformer);
		$this->containerBuilderTransformer = new ContainerBuilderTransformer($serviceDefinitionTransformer);
	}


	public function testAddingAlreadyExistingService()
	{
		$netteContainerBuilder = new ContainerBuilder;
		$netteContainerBuilder->addDefinition('someservice')
			->setClass(stdClass::class)
			->setAutowired(FALSE);

		$symfonyContainerBuilder = new SymfonyContainerBuilder;
		$this->containerBuilderTransformer->transformFromNetteToSymfony(
			$netteContainerBuilder, $symfonyContainerBuilder
		);

		$this->containerBuilderTransformer->transformFromSymfonyToNette(
			$symfonyContainerBuilder, $netteContainerBuilder
		);

		$netteDefinition = $netteContainerBuilder->getDefinition('someservice');
		$this->assertSame(stdClass::class, $netteDefinition->getClass());

		$symfonyDefinition = $symfonyContainerBuilder->getDefinition('someservice');
		$this->assertSame(stdClass::class, $symfonyDefinition->getClass());

		$this->assertSame($netteDefinition->getClass(), $symfonyDefinition->getClass());
	}


	public function testTags()
	{
		$netteContainerBuilder = new ContainerBuilder;
		$netteDefinition = $netteContainerBuilder->addDefinition('someService')
			->setClass(stdClass::class)
			->addTag('someTag');

		$symfonyContainerBuilder = new SymfonyContainerBuilder;
		$this->containerBuilderTransformer->transformFromNetteToSymfony(
			$netteContainerBuilder, $symfonyContainerBuilder
		);

		$symfonyContainerBuilder->compile();

		$symfonyDefinition = $symfonyContainerBuilder->getDefinition('someService');
		$this->assertSame(['someTag' => [[TRUE]]], $symfonyDefinition->getTags());

		$this->containerBuilderTransformer->transformFromSymfonyToNette(
			$symfonyContainerBuilder, $netteContainerBuilder
		);

		$this->assertSame($netteDefinition, $netteContainerBuilder->getDefinition('someService'));
	}

}
