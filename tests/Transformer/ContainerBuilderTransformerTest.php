<?php

namespace Symplify\NetteAdapaterForSymfonyBundles\Tests\Transformer;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Nette\DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symplify\NetteAdapaterForSymfonyBundles\Tests\Transformer\ContainerBuilderTransformerSource\AutowireReader;
use Symplify\NetteAdapaterForSymfonyBundles\Transformer\ArgumentsTransformer;
use Symplify\NetteAdapaterForSymfonyBundles\Transformer\ContainerBuilderTransformer;
use Symplify\NetteAdapaterForSymfonyBundles\Transformer\ServiceDefinitionTransformer;

final class ContainerBuilderTransformerTest extends TestCase
{
    /**
     * @var ContainerBuilderTransformer
     */
    private $containerBuilderTransformer;

    protected function setUp()
    {
        $serviceDefinitionTransformer = new ServiceDefinitionTransformer(new ArgumentsTransformer());
        $this->containerBuilderTransformer = new ContainerBuilderTransformer($serviceDefinitionTransformer);
    }

    public function testAddingAlreadyExistingService()
    {
        $netteContainerBuilder = new ContainerBuilder();
        $netteContainerBuilder->addDefinition('someservice')
            ->setClass(stdClass::class)
            ->setAutowired(false);

        $symfonyContainerBuilder = new SymfonyContainerBuilder();
        $this->containerBuilderTransformer->transformFromNetteToSymfony(
            $netteContainerBuilder,
            $symfonyContainerBuilder
        );

        $this->containerBuilderTransformer->transformFromSymfonyToNette(
            $symfonyContainerBuilder,
            $netteContainerBuilder
        );

        $netteDefinition = $netteContainerBuilder->getDefinition('someservice');
        $this->assertSame(stdClass::class, $netteDefinition->getClass());

        $symfonyDefinition = $symfonyContainerBuilder->getDefinition('someservice');
        $this->assertSame(stdClass::class, $symfonyDefinition->getClass());

        $this->assertSame($netteDefinition->getClass(), $symfonyDefinition->getClass());
    }

    public function testTags()
    {
        $netteContainerBuilder = new ContainerBuilder();
        $netteDefinition = $netteContainerBuilder->addDefinition('someService')
            ->setClass(stdClass::class)
            ->addTag('someTag');

        $symfonyContainerBuilder = new SymfonyContainerBuilder();
        $this->containerBuilderTransformer->transformFromNetteToSymfony(
            $netteContainerBuilder,
            $symfonyContainerBuilder
        );

        $symfonyContainerBuilder->compile();

        $symfonyDefinition = $symfonyContainerBuilder->getDefinition('someService');
        $this->assertSame(['someTag' => [[true]]], $symfonyDefinition->getTags());

        $this->containerBuilderTransformer->transformFromSymfonyToNette(
            $symfonyContainerBuilder,
            $netteContainerBuilder
        );

        $this->assertSame($netteDefinition, $netteContainerBuilder->getDefinition('someService'));
    }

    public function testAutowiringStep2()
    {
        $netteContainerBuilder = new ContainerBuilder();
        $netteDefinition = $netteContainerBuilder->addDefinition('someService')
            ->setClass(stdClass::class)
            ->addTag('someTag');

        $symfonyContainerBuilder = new SymfonyContainerBuilder();
        $this->containerBuilderTransformer->transformFromNetteToSymfony(
            $netteContainerBuilder,
            $symfonyContainerBuilder
        );

        $symfonyContainerBuilder->compile();

        $symfonyDefinition = $symfonyContainerBuilder->getDefinition('someService');
        $this->assertSame(['someTag' => [[true]]], $symfonyDefinition->getTags());

        $this->containerBuilderTransformer->transformFromSymfonyToNette(
            $symfonyContainerBuilder,
            $netteContainerBuilder
        );

        $this->assertSame($netteDefinition, $netteContainerBuilder->getDefinition('someService'));
    }

    public function testPreventDuplicating()
    {
        $netteContainerBuilder = new ContainerBuilder();
        $netteContainerBuilder->addDefinition('annotationReader')
            ->setClass(AnnotationReader::class)
            ->setAutowired(false);

        $netteContainerBuilder->addDefinition('reader')
            ->setClass(Reader::class)
            ->setFactory(CachedReader::class);

        $netteContainerBuilder->addDefinition('autowireReader')
            ->setClass(AutowireReader::class);

        $symfonyContainerBuilder = new SymfonyContainerBuilder();
        $this->containerBuilderTransformer->transformFromNetteToSymfony(
            $netteContainerBuilder,
            $symfonyContainerBuilder
        );

        $this->assertCount(3, $netteContainerBuilder->getDefinitions());

        $symfonyContainerBuilder->compile();

        $this->containerBuilderTransformer->transformFromSymfonyToNette(
            $symfonyContainerBuilder,
            $netteContainerBuilder
        );

        $this->assertCount(3, $netteContainerBuilder->getDefinitions());

        $netteContainerBuilder->prepareClassList();
        $readerDefinition = $netteContainerBuilder->getDefinition($netteContainerBuilder->getByType(Reader::class));
        $this->assertSame(Reader::class, $readerDefinition->getClass());
    }
}
