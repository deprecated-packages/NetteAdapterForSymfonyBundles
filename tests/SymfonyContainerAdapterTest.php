<?php

namespace Symnedi\SymfonyBundlesExtension\Tests;

use Nette\DI\Container;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\DependencyInjection\ScopeInterface;
use Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;


final class SymfonyContainerAdapterTest extends TestCase
{

	/**
	 * @var SymfonyContainerAdapter
	 */
	private $symfonyContainerAdapter;


	protected function setUp()
	{
		$containerMock = $this->prophesize(Container::class);
		$containerMock->getParameters()->willReturn(['someParameter' => 'someValue']);
		$containerMock->hasService('someService')->willReturn(TRUE);
		$containerMock->hasService('nonExistingService')->willReturn(FALSE);
		$containerMock->getService('someService')->willReturn('service');
		$this->symfonyContainerAdapter = new SymfonyContainerAdapter([], $containerMock->reveal());
	}


	public function testParameters()
	{
		$this->assertSame('someValue', $this->symfonyContainerAdapter->getParameter('someParameter'));
		$this->assertTrue($this->symfonyContainerAdapter->hasParameter('someParameter'));
	}


	/**
	 * @expectedException \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
	 */
	public function testNonExistingParameters()
	{
		$this->symfonyContainerAdapter->getParameter('nonExistingParameter');
	}


	public function testServices()
	{
		$this->assertTrue($this->symfonyContainerAdapter->has('someService'));
		$this->assertSame('service', $this->symfonyContainerAdapter->get('someService'));
	}


	/**
	 * @expectedException \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
	 */
	public function testNonExistingService()
	{
		$this->assertFalse($this->symfonyContainerAdapter->has('nonExistingService'));
		$this->symfonyContainerAdapter->get('nonExistingService');
	}


	/**
	 * @expectedException \Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException
	 */
	public function testUnsupportedMethodsAddScope()
	{
		$scopeMock = $this->prophesize(ScopeInterface::class);

		$this->symfonyContainerAdapter->addScope($scopeMock->reveal());
	}


	/**
	 * @expectedException \Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException
	 */
	public function testUnsupportedMethodsEnterScope()
	{
		$this->symfonyContainerAdapter->enterScope('someScope');
	}


	/**
	 * @expectedException \Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException
	 */
	public function testUnsupportedMethodsHasScope()
	{
		$this->setExpectedException(UnsupportedApiException::class);
		$this->symfonyContainerAdapter->hasScope('someScope');
	}


	/**
	 * @expectedException \Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException
	 */
	public function testUnsupportedMethodsLeaveScope()
	{
		$this->symfonyContainerAdapter->leaveScope('someScope');
	}


	/**
	 * @expectedException \Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException
	 */
	public function testUnsupportedMethodsIsScopeActive()
	{
		$this->symfonyContainerAdapter->isScopeActive('someScope');
	}


	/**
	 * @expectedException \Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException
	 */
	public function testUnsupportedMethodsSet()
	{
		$this->symfonyContainerAdapter->set('someService', new stdClass);
	}


	/**
	 * @expectedException \Symnedi\SymfonyBundlesExtension\Exception\UnsupportedApiException
	 */
	public function testUnsupportedMethodsSetParameter()
	{
		$this->symfonyContainerAdapter->setParameter('someParameter', 'someValue');
	}

}
