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
		$compiler = new Compiler(new ContainerBuilder);
		$this->extension->setCompiler($compiler, 'symfonyBundles');

		// simulates required Nette\Configurator default parameters
		$compiler->addConfig([
			'parameters' => [
				'appDir' => '',
				'tempDir' => '',
				'debugMode' => TRUE,
				'environment' => ''
			]
		]);
	}


	public function testLoadBundlesEmpty()
	{
		$bundles = (new Loader)->load(__DIR__ . '/SymfonyBundlesExtensionSource/bundles.neon');
		$this->extension->setConfig($bundles);
		$this->extension->loadConfiguration();
		$this->extension->beforeCompile();

		$builder = $this->extension->getContainerBuilder();
		$this->assertCount(17, $builder->getDefinitions());
	}

}
