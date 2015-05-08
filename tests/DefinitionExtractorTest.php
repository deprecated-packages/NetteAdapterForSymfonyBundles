<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Hautelook\AliceBundle\HautelookAliceBundle;
use PHPUnit_Framework_TestCase;
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
		$this->definitionExtractor = new DefinitionExtractor;
	}


	public function testExtractFromBundles()
	{
		$bundles = [HautelookAliceBundle::class];

		$definitions = $this->definitionExtractor->extractFromBundles($bundles);
		$this->assertCount(3, $definitions);
	}


	public function testExtractFromBundle()
	{
		$bundle = new HautelookAliceBundle;

		$definitions = $this->definitionExtractor->extractFromBundle($bundle);
		$this->assertCount(3, $definitions);
	}


	public function testExtractFromExtension()
	{
		$bundle = new HautelookAliceBundle;
		$extension = $bundle->getContainerExtension();

		$definitions = $this->definitionExtractor->extractFromExtension($extension);
		$this->assertCount(3, $definitions);
	}

}
