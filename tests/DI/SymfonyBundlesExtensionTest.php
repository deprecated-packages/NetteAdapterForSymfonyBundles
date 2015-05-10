<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Nette\DI\Compiler;
use Nette\DI\Config\Loader;
use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\DI\SymfonyBundlesExtension;


class SymfonyBundlesExtensionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var SymfonyBundlesExtension
	 */
	private $extension;


	protected function setUp()
	{
		$this->extension = new SymfonyBundlesExtension;
		$this->extension->setCompiler(new Compiler(new ContainerBuilder), 'symfonyBundles');
	}


	public function testPersisUniqueName()
	{
		$builder = $this->extension->getContainerBuilder();
		$builder->addDefinition('doctrine.orm.entity_manager');

		$bundles = (new Loader)->load(__DIR__ . '/SymfonyBundlesExtensionSource/bundles.neon');
		$this->extension->setConfig($bundles);
		$this->extension->loadConfiguration();

		$this->assertTrue($builder->hasDefinition('doctrine.orm.entity_manager'));
		$this->assertTrue($builder->hasDefinition('symfonyBundles.doctrine.orm.entity_manager'));
	}


	public function testLoadBundlesEmpty()
	{
		$bundles = (new Loader)->load(__DIR__ . '/SymfonyBundlesExtensionSource/bundles.neon');
		$this->extension->setConfig($bundles);
		$this->extension->loadConfiguration();

		$builder = $this->extension->getContainerBuilder();
		$this->assertCount(17, $builder->getDefinitions());
	}

}
