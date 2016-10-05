<?php

declare(strict_types=1);

/*
 * This file is part of Symplify.
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symplify\NetteAdapaterForSymfonyBundles\Compiler;

use ReflectionClass;
use stdClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass will disable all missing references to named services.
 * Missing parameter resolution shall be resolved in Nette's ContainerBuilder.
 *
 * Based on {@see Symfony\Component\DependencyInjection\Compiler\CheckExceptionOnInvalidReferenceBehaviorPass}
 */
final class FakeReferencesPass implements CompilerPassInterface
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->container = $container;

        foreach ($container->getDefinitions() as $id => $definition) {
            $this->processDefinition($definition);
        }
    }

    private function processDefinition(Definition $definition)
    {
        $this->processReferences($definition->getArguments());
        $this->processReferences($definition->getMethodCalls());
        $this->processReferences($definition->getProperties());
    }

    private function processReferences(array $arguments)
    {
        foreach ($arguments as $argument) {
            if (is_array($argument)) {
                $this->processReferences($argument);
            } elseif ($argument instanceof Definition) {
                $this->processDefinition($argument);
            } elseif ($this->isMissingDefinitionReference($argument)) {
                $this->addMissingDefinitionReferenceToContainer($argument);
            }
        }
    }

    /**
     * @param mixed $argument
     */
    private function isMissingDefinitionReference($argument)
    {
        return $argument instanceof Reference
            && $argument->getInvalidBehavior() === ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
    }

    private function addMissingDefinitionReferenceToContainer(Reference $argument)
    {
        $serviceName = (string) $argument;
        if (class_exists($serviceName)) {
            $serviceName = (new ReflectionClass($serviceName))->name;
        }

        if (!$this->container->has($serviceName)) {
            $this->container->setDefinition($serviceName, new Definition(stdClass::class));
        }
    }
}
