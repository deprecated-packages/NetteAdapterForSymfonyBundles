<?php

namespace Symplify\NetteAdapaterForSymfonyBundles\Tests\ContainerSource;

final class AutowiredService
{
    /**
     * @var SomeService
     */
    private $someService;

    public function __construct(SomeService $someService)
    {
        $this->someService = $someService;
    }

    public function getSomeService() : SomeService
    {
        return $this->someService;
    }
}
