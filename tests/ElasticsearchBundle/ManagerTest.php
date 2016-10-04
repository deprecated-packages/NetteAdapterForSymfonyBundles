<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\ElasticsearchBundle;

use Elasticsearch\Client;
use Nette\DI\Container;
use ONGR\ElasticsearchBundle\Client\Connection;
use ONGR\ElasticsearchBundle\ORM\Manager;
use PHPUnit\Framework\TestCase;
use Symnedi\SymfonyBundlesExtension\Tests\ContainerFactory;


final class ManagerTest extends TestCase
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

		$this->assertInstanceOf(Client::class, $connection->getClient());
	}

}
