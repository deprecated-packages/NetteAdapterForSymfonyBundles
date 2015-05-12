<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\Container\ParametersSource;

use League\Tactician\Middleware;


class CustomMiddleware implements Middleware
{

	/**
	 * {@inheritdoc}
	 */
	public function execute($command, callable $next)
	{
//		var_dump($command);
//		die;
	}

}
