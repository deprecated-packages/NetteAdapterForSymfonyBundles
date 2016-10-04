<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\ElasticsearchBundle;

use Nette\DI\Container;
use ONGR\ElasticsearchBundle\Client\IndexSuffixFinder;
use PHPUnit\Framework\TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;


final class InitTest extends TestCase
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
