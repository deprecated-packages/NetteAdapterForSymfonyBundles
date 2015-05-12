<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\TacticianBundle;

use Closure;
use League\Tactician\CommandBus;
use Nette\DI\Container;
use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use stdClass;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerSource\SomeCommand;


class NetteServiceAliasTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->createWithConfig(__DIR__ . '/config/netteServiceAlias.neon');
	}


	public function testSymfonyServiceReferencing()
	{
		/** @var CommandBus $commandBus */
		$commandBus = $this->container->getByType(CommandBus::class);
		$this->assertInstanceOf(CommandBus::class, $commandBus);

		/** @var Closure $middlewareChain */
		$middlewareChain = PHPUnit_Framework_Assert::getObjectAttribute($commandBus, 'middlewareChain');

		$output = $middlewareChain(new stdClass);
		$this->assertInstanceOf(stdClass::class, $output);
	}

}
