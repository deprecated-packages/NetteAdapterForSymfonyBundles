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
	}

}
