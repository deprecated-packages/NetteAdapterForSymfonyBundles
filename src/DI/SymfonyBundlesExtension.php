<?php

/*
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symnedi\SymfonyBundlesExtension\DI;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symnedi\SymfonyBundlesExtension\SymfonyContainerAdapter;
use Symnedi\SymfonyBundlesExtension\Transformer\ContainerBuilderTransformer;
use Symnedi\SymfonyBundlesExtension\Transformer\DI\TransformerFactory;
use Symnedi\SymfonyBundlesExtension\Transformer\ParametersTransformer;

final class SymfonyBundlesExtension extends CompilerExtension
{
    /**
     * @var string
     */
    const SYMFONY_CONTAINER_SERVICE_NAME = 'service_container';

    /**
     * @var array[]
     */
    private $defaults = [
        'bundles' => [],
        'parameters' => [],
    ];

    /**
     * @var Bundle[]
     */
    private $bundles = [];

    /**
     * @var SymfonyContainerBuilder
     */
    private $symfonyContainerBuilder;

    /**
     * @var ContainerBuilderTransformer
     */
    private $containerBuilderTransformer;

    /**
     * @var ParametersTransformer
     */
    private $parametersTransformer;

    /**
     * Mirror to compiler passes.
     */
    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);
        $this->initialize($config['bundles']);

        $this->parametersTransformer->transformFromNetteToSymfony($this->compiler, $config);
        $this->loadBundlesToSymfonyContainerBuilder($config['parameters']);
    }

    /**
     * Mirror to $bundle->compile().
     */
    public function beforeCompile()
    {
        $this->containerBuilderTransformer->transformFromNetteToSymfony(
            $this->getContainerBuilder(),
            $this->symfonyContainerBuilder
        );

        $this->addSymfonyContainerAdapter();
        $this->symfonyContainerBuilder->compile();

        $this->containerBuilderTransformer->transformFromSymfonyToNette(
            $this->symfonyContainerBuilder,
            $this->getContainerBuilder()
        );
    }

    /**
     * Mirror to $bundle->boot().
     */
    public function afterCompile(ClassType $class)
    {
        $initializerMethod = $class->getMethod('initialize');
        $initializerMethod->addBody('
			foreach (? as $bundle) {
				$bundle->setContainer($this->getService(?));
				$bundle->boot();
			}', [$this->bundles, self::SYMFONY_CONTAINER_SERVICE_NAME]);
    }

    /**
     * @param string[] $bundles
     */
    private function initialize(array $bundles)
    {
        $tempDir = $this->compiler->getConfig()['parameters']['tempDir'];
        $transformer = (new TransformerFactory($this->getContainerBuilder(), $tempDir))->create();

        $this->symfonyContainerBuilder = $transformer->getByType(SymfonyContainerBuilder::class);
        $this->containerBuilderTransformer = $transformer->getByType(ContainerBuilderTransformer::class);
        $this->parametersTransformer = $transformer->getByType(ParametersTransformer::class);

        foreach ($bundles as $name => $class) {
            $this->bundles[$name] = new $class();
        }
    }

    private function loadBundlesToSymfonyContainerBuilder(array $parameters)
    {
        foreach ($this->bundles as $name => $bundle) {
            if ($extension = $bundle->getContainerExtension()) {
                $this->symfonyContainerBuilder->registerExtension($extension);
                $extensionParameters = $this->determineParameters($parameters, $name);
                $this->symfonyContainerBuilder->loadFromExtension($extension->getAlias(), $extensionParameters);
            }
            $bundle->build($this->symfonyContainerBuilder);
        }
    }

    private function determineParameters(array $parameters, string $name) : array
    {
        return $parameters[$name] ?? [];
    }

    private function addSymfonyContainerAdapter()
    {
        $this->getContainerBuilder()
            ->addDefinition(self::SYMFONY_CONTAINER_SERVICE_NAME)
            ->setClass(SymfonyContainerAdapter::class)
            ->setArguments([$this->getSymfonyToNetteServiceAliases()]);
    }

    /**
     * @return string[] {[ Symfony name => Nette name ]}
     */
    private function getSymfonyToNetteServiceAliases() : array
    {
        $names = [];
        foreach ($this->getContainerBuilder()->getDefinitions() as $name => $definition) {
            $names[strtolower($name)] = $name;
        }

        return $names;
    }
}
