<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension;

use Nette\DI\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\ScopeInterface;
use Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException;


class SymfonyContainerAdapter implements ContainerInterface
{

	/**
	 * @var Container
	 */
	private $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	/**
	 * {@inheritdoc}
	 */
	public function set($id, $service, $scope = self::SCOPE_CONTAINER)
	{
		throw new UnsupportedApiException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
	{
		if ($this->has($id)) {
			return $this->container->getService($id);
		}

		throw new ServiceNotFoundException(
			sprintf('Service "%s" was not found.', $id)
		);
	}


	/**
	 * {@inheritdoc}
	 */
	public function has($id)
	{
		return $this->container->hasService($id);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameter($name)
	{
		if ($this->hasParameter($name)) {
			return $this->container->getParameters()[$name];
		}

		throw new InvalidArgumentException(
			sprintf('Parameter "%s" was not found.', $name)
		);
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasParameter($name)
	{
		return isset($this->container->getParameters()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function setParameter($name, $value)
	{
		throw new UnsupportedApiException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function enterScope($name)
	{
		throw new UnsupportedApiException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function leaveScope($name)
	{
		throw new UnsupportedApiException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function addScope(ScopeInterface $scope)
	{
		throw new UnsupportedApiException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasScope($name)
	{
		throw new UnsupportedApiException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isScopeActive($name)
	{
		throw new UnsupportedApiException;
	}

}
