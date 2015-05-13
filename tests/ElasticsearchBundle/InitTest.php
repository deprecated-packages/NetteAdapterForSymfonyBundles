<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\ElasticsearchBundle;

use Nette\DI\Container;
use ONGR\ElasticsearchBundle\Client\IndexSuffixFinder;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;


class InitTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->createWithConfig(__DIR__ . '/config/init.neon');
	}


	public function testGetService()
	{
		/** @var IndexSuffixFinder $indexSuffixFinder */
		$indexSuffixFinder = $this->container->getByType(IndexSuffixFinder::class);
		$this->assertInstanceOf(IndexSuffixFinder::class, $indexSuffixFinder);
	}

}
