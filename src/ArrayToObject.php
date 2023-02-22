<?php

namespace src;

use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

class ArrayToObject
{
    /**
     * @template T
     * @param array $param
     * @param class-string<T> $class
     * @return T
     */
    public function convert(array $param, string $class)
    {
        $obj = new $class();

        $cursor = new Cursor($param, $class, $obj);
        reset($cursor->param);

        $tmp = [];
        $i = 0;
        while (true) {
            $key = key($cursor->param);

            if ($key === null && $cursor->parent === null) {
                break;
            }
            if ($key === null) {
                $cursor = $cursor->parent;
                next($cursor->param);
                continue;
            }

            $reflectionNamedType = $this->getType($cursor->class, $key);
            $typeName = $reflectionNamedType->getName();

            if (!$reflectionNamedType->isBuiltin()) {
                if (is_int($key)) {
                    $cursor->value[$key] = new $typeName();
                    $value = $cursor->value[$key];
                } else {
                    $cursor->value->{$key} = new $typeName();
                    $value = $cursor->value->{$key};
                }

                $_cursor = new Cursor(current($cursor->param), $typeName, $value);
                $_cursor->parent = $cursor;
                $cursor = $_cursor;
                reset($cursor->param);
            } elseif ($typeName === 'array') {
                $tmp[$i] = [];
                if (is_int($key)) {
                    $cursor->value[$key] = &$tmp[$i];
                } else {
                    $cursor->value->{$key} = &$tmp[$i];
                }

                if (!($cursor->value instanceof ArrayTypesInterface)) {
                    throw new RuntimeException('Type is not defined');
                }

                $_cursor = new Cursor(current($cursor->param), $cursor->value->arrayTypes()[$key]);
                $_cursor->value = &$tmp[$i];
                $_cursor->parent = $cursor;
                $cursor = $_cursor;
                reset($cursor->param);

                $i++;
            } else {
                if (is_int($key)) {
                    $cursor->value[$key] = call_user_func("{$cursor->class}val", current($cursor->param));
                } else {
                    $cursor->value->{$key} = current($cursor->param);
                }

                next($cursor->param);
            }
        }

        return $cursor->value;
    }

    protected function getType(string $class, string $property): ReflectionNamedType
    {
        if ((string)(int)$property === $property) {
            return new class($class) extends ReflectionNamedType {
                protected string $_name;
                protected bool $_isBuiltin;

                public function __construct($name)
                {
                    $this->_name = $name;
                    $this->_isBuiltin = !class_exists($name);
                }

                public function getName(): string
                {
                    return $this->_name;
                }

                public function isBuiltin(): bool
                {
                    return $this->_isBuiltin;
                }
            };
        }

        try {
            $rp = new ReflectionProperty($class, $property);
        } catch (ReflectionException $e) {
            echo $e->getTraceAsString();
            throw new RuntimeException("ReflectionException occurred");
        }

        $type = $rp->getType();
        if ($type === null) {
            throw new RuntimeException("The property $property is not type-defined");
        }

        return $type;
    }
}
