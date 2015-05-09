<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\ContainerSource;

use Hautelook\AliceBundle\Alice\Loader;


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
