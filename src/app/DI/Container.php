<?php

namespace Dangje\WebFramework\DI;

use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{
    private $objects = [];

    public function has(string $id): bool {
        return isset($this->objects[$id]) || class_exists($id);
    }

    public function get(string $id)
    {
        return 
            isset($this->objects[$id]) 
            ? $this->objects[$id]()
            : $this->prepareObject($id);
    }

    // private function set(string $class, ...$args): object {
    //     $object = $args ? new $class(...$args) : new $class();
    //     $this->objects[$class] = $object;
    //     return $object;
    // }

    private function prepareObject(string $class): object {
        $classReflector = new ReflectionClass($class);

        $construct = $classReflector->getConstructor();
        if (empty($construct)) {
            // return $this->set($class);
            return new $class();
        }

        $constructArguments = $construct->getParameters();
        if (empty($constructArguments)) {
            // return $this->set($class);
            return new $class();
        }

        $args = [];
        foreach ($constructArguments as $parameter) {
            $parameterType = $parameter->getType();

            if ($parameterType !== null) {
                $parameterTypeName = $parameterType->getName();

                if (class_exists($parameterTypeName)) {
                    $args[$parameter->getName()] = $this->get($parameterTypeName);
                    if ($parameterTypeName === 'int') {
                        $args[$parameter->getName()] = 0;
                    } elseif ($parameterTypeName === 'string') {
                        $args[$parameter->getName()] = '';
                    } elseif ($parameterTypeName === 'bool') {
                        $args[$parameter->getName()] = false;
                    } elseif ($parameterTypeName === 'float') {
                        $args[$parameter->getName()] = 0.0;
                    } else {
                        throw new \Exception("Неизвестный тип параметра '$parameterTypeName' для параметра '{$parameter->getName()}'.");
                    }
                }
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $args[$parameter->getName()] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Параметр '{$parameter->getName()}' не имеет типа и значения по умолчанию.");
                }
            }
        }
        
        // foreach ($constructArguments as $argument) {

        //     $argumentType = $argument->getType();

        //     $argumentName = $argument->getType()->getName();

        //     if (class_exists($argumentType)) {
        //         $args[$argumentName] = $this->get($argumentType);
        //     } else {
        //         if ($argumentType === 'int') {
        //             $args[$argumentName] = 0;
        //         } elseif ($argumentType === 'string') {
        //             $args[$argumentName] = '';
        //         } elseif ($argumentType === 'bool') {
        //             $args[$argumentName] = false;
        //         } elseif ($argumentType === 'float') {
        //             $args[$argumentName] = 0.0;
        //         } else {
        //             throw new \Exception("Неизвестный тип параметра '$argumentType' для параметра '{$argumentName}'.");
        //         }
        //     }

        //     $args[$argument->getName()] = $this->get($argumentType);
        // }

        //return $this->set($class, ...$args);
        return new $class(...$args);
    }
}