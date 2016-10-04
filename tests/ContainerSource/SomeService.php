<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\ContainerSource;


use Doctrine\Common\DataFixtures\Loader;


class AutowiredService
{

	/**
	 * @var Loader
	 */
	private $loader;


	public function __construct(Loader $loader)
	{
		$this->loader = $loader;
	}


	/**
	 * @return Loader
	 */
	public function getLoader()
	{
		return $this->loader;
	}

}
