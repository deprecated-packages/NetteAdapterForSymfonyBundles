<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\ElasticsearchBundle;

use Nette\DI\Container;
use ONGR\ElasticsearchBundle\Client\Connection;
use ONGR\ElasticsearchBundle\ORM\Manager;
use PHPUnit_Framework_TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;


class ManagerTest extends PHPUnit_Framework_TestCase
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
		/** @var Manager $manager */
		$manager = $this->container->getService('es.manager.default');
		$this->assertInstanceOf(Manager::class, $manager);

		/** @var Connection $connection */
		$connection = $manager->getConnection();
		$this->assertInstanceOf(Connection::class, $connection);

//		$connection->createIndex($input->getOption('with-warmers'), $input->getOption('no-mapping') ? true : false);
	}

}
