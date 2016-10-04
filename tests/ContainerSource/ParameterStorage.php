<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\ContainerSource;


final class ParameterStorage
{

	/**
	 * @var string
	 */
	private $parameter;

	/**
	 * @var array
	 */
	private $groupOfParameters;


	/**
	 * @param string $parameter
	 * @param array $groupOfParameters
	 */
	public function __construct($parameter, array $groupOfParameters)
	{
		$this->parameter = $parameter;
		$this->groupOfParameters = $groupOfParameters;
	}


	/**
	 * @return string
	 */
	public function getParameter()
	{
		return $this->parameter;
	}


	/**
	 * @return array
	 */
	public function getGroupOfParameters()
	{
		return $this->groupOfParameters;
	}

}
