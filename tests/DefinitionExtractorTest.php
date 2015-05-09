<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Hautelook\AliceBundle\HautelookAliceBundle;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symnedi\SymfonyBundlesExtension\Contract\DefinitionExtractorInterface;
use Symnedi\SymfonyBundlesExtension\DefinitionExtractor;


class DefinitionExtractorTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var DefinitionExtractorInterface
	 */
	private $definitionExtractor;


	protected function setUp()
	{
		$this->definitionExtractor = new DefinitionExtractor(new ContainerBuilder);
	}


	public function testExtractFromBundles()
	{
		$bundles = [HautelookAliceBundle::class];

		$definitions = $this->definitionExtractor->extractFromBundles($bundles);
		$this->assertCount(3, $definitions);
	}

}
