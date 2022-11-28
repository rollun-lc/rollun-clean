<?php

namespace Clean\Common\Frameworks\Factories;

use Clean\Common\Application\Interfaces\MapperInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SymfonyMapperAbstractFactory implements AbstractFactoryInterface
{
    // Factory components

    public const KEY = self::class;

    public const KEY_CLASS = 'class';

    public const KEY_DEPENDENCIES = 'dependencies';

    public const BASE_CLASS = MapperInterface::class;

    // SymfonyMapper components

    public const KEY_SERIALIZER = 'serializer';

    public const KEY_LOADER_CLASS = 'loaderClass';

    public const KEY_READER_CLASS = 'readerClass';

    public const KEY_LOADER_FILE = 'loaderFile';

    public const KEY_NAME_CONVERTER = 'nameConverter';

    public const KEY_WITH_MAGIC = 'withMagic';

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? null;
        if (!$config) {
            return false;
        }

        $className = $config[self::KEY_CLASS] ?? $requestedName;

        return class_exists($className) && is_a($className, self::BASE_CLASS, true);
    }

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? null;
        $className = $config[self::KEY_CLASS] ?? $requestedName;

        $dependencies = [];

        $reflectionClass = new \ReflectionClass($className);
        if ($constructor = $reflectionClass->getConstructor()) {
            foreach ($config[self::KEY_DEPENDENCIES] as $name => $value) {
                if ($parameter = $this->getMethodParameterByName($constructor, $name)) {
                    if ($parameter->hasType() && !$parameter->getType()?->isBuiltin()) {
                        $dependency = $container->get($parameter->getType()?->getName());
                        $dependencies[$name] = $dependency;
                    } elseif (isset($config[self::KEY_DEPENDENCIES][$name])) {
                        $dependencies[$name] = $config[self::KEY_DEPENDENCIES][$name];
                    } elseif ($parameter->isDefaultValueAvailable()) {
                        $dependencies[$name] = $parameter->getDefaultValue();
                    } elseif ($parameter->isOptional()) {
                        $dependencies[$name] = null;
                    }
                }
            }
        }

        if (!isset($dependencies[self::KEY_SERIALIZER])) {
            $dependencies[self::KEY_SERIALIZER] = $this->createSerializer($config);
        }

        $dependencies[self::KEY_WITH_MAGIC] = $config[self::KEY_WITH_MAGIC] ?? true;

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    protected function getMethodParameterByName(\ReflectionMethod $method, $name)
    {
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->getName() === $name) {
                return $parameter;
            }
        }

        return null;
    }

    protected function createSerializer($config)
    {
        $loaderClass = $config[self::KEY_LOADER_CLASS] ?? AnnotationLoader::class;
        if (is_a($loaderClass, AnnotationLoader::class, true)) {
            $readerClass = $config[self::KEY_READER_CLASS] ?? AnnotationReader::class;
            $reader = new $readerClass();
        } else {
            $reader = $config[self::KEY_LOADER_FILE] ?? null;
            if (!$reader) {
                throw new ServiceNotCreatedException(
                    'Parameter "' . self::KEY_LOADER_FILE . '" is required for loader "' . $loaderClass
                );
            }
        }

        $loader = new $loaderClass($reader);

        /*$context = [
            AbstractObjectNormalizer::CALLBACKS => [
                'created_at' => function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
                    return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
                },
                'updated_at' => function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
                    return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
                }
            ]
        ];*/

        $context = [
            ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            /*ObjectNormalizer::CALLBACKS => [
                'created_at' => function($value) {
                    return new DateTime($value);
                },
                'updated_at' => function($value) {
                    return new DateTime($value);
                }
            ]*/
        ];

        $classMetadataFactory = new ClassMetadataFactory($loader);
        $nameConverter = isset($config[self::KEY_NAME_CONVERTER]) ? new $config[self::KEY_NAME_CONVERTER]() : null;
        $normalizer = new ObjectNormalizer(
            $classMetadataFactory,
            $nameConverter,
            null,
            new ReflectionExtractor(),
            null,
            null,
            $context
        );

        return new Serializer([new DateTimeNormalizer(), $normalizer]);
    }
}