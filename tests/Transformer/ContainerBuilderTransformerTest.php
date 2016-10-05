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
     * @var ContainerBuilder
     */
    private $netteContainerBuilder;

    /**
     * @var SymfonyContainerBuilder
     */
    private $symfonyContainerBuilder;

    /**
     * @var ContainerBuilderTransformer
     */
    private $containerBuilderTransformer;

    protected function setUp()
    {
        $this->containerBuilderTransformer = $this->createContainerBuilderTransformer();

        $this->netteContainerBuilder = new ContainerBuilder();
        $this->symfonyContainerBuilder = new SymfonyContainerBuilder();
    }

    public function testAddingAlreadyExistingService()
    {
        $this->netteContainerBuilder->addDefinition('someservice')
            ->setClass(stdClass::class)
            ->setAutowired(false);

        $this->transformFromNetteToSymfonyAndCompile();

        $this->transformFromSymfonyToNette();

        $netteDefinition = $this->netteContainerBuilder->getDefinition('someservice');
        $this->assertSame(stdClass::class, $netteDefinition->getClass());

        $symfonyDefinition = $this->symfonyContainerBuilder->getDefinition('someservice');
        $this->assertSame(stdClass::class, $symfonyDefinition->getClass());

        $this->assertSame($netteDefinition->getClass(), $symfonyDefinition->getClass());
    }

    public function testTags()
    {
        $netteDefinition = $this->netteContainerBuilder->addDefinition('someService')
            ->setClass(stdClass::class)
            ->addTag('someTag');

        $this->transformFromNetteToSymfonyAndCompile();

        $symfonyDefinition = $this->symfonyContainerBuilder->getDefinition('someService');
        $this->assertSame(['someTag' => [[true]]], $symfonyDefinition->getTags());

        $this->transformFromSymfonyToNette();

        $this->assertSame($netteDefinition, $this->netteContainerBuilder->getDefinition('someService'));
    }

    public function testAutowiringStep2()
    {
        $netteDefinition = $this->netteContainerBuilder->addDefinition('someService')
            ->setClass(stdClass::class)
            ->addTag('someTag');

        $this->transformFromNetteToSymfonyAndCompile();

        $symfonyDefinition = $this->symfonyContainerBuilder->getDefinition('someService');
        $this->assertSame(['someTag' => [[true]]], $symfonyDefinition->getTags());

        $this->transformFromSymfonyToNette();

        $this->assertSame($netteDefinition, $this->netteContainerBuilder->getDefinition('someService'));
    }

    public function testPreventDuplicating()
    {
        $this->netteContainerBuilder->addDefinition('annotationReader')
            ->setClass(AnnotationReader::class)
            ->setAutowired(false);

        $this->netteContainerBuilder->addDefinition('reader')
            ->setClass(Reader::class)
            ->setFactory(CachedReader::class);

        $this->netteContainerBuilder->addDefinition('autowireReader')
            ->setClass(AutowireReader::class);

        $this->transformFromNetteToSymfonyAndCompile();

        $this->transformFromSymfonyToNette();

        $this->assertCount(3, $this->netteContainerBuilder->getDefinitions());

        $this->netteContainerBuilder->prepareClassList();
        $readerDefinition = $this->netteContainerBuilder->getDefinition(
            $this->netteContainerBuilder->getByType(Reader::class)
        );
        $this->assertSame(Reader::class, $readerDefinition->getClass());
    }

    private function createContainerBuilderTransformer() : ContainerBuilderTransformer
    {
        $serviceDefinitionTransformer = new ServiceDefinitionTransformer(new ArgumentsTransformer());

        return new ContainerBuilderTransformer($serviceDefinitionTransformer);
    }

    private function transformFromNetteToSymfonyAndCompile()
    {
        $this->containerBuilderTransformer->transformFromNetteToSymfony(
            $this->netteContainerBuilder,
            $this->symfonyContainerBuilder
        );

        $this->symfonyContainerBuilder->compile();
    }

    private function transformFromSymfonyToNette()
    {
        $this->containerBuilderTransformer->transformFromSymfonyToNette(
            $this->symfonyContainerBuilder,
            $this->netteContainerBuilder
        );
    }
}
