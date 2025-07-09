<?php

namespace App\Config;

use ReflectionClass;
use Exception;


class Container {
    private static ?self $instance = null;

    private array $bindings = [];

    private function __construct() {}

    public static function getInstance(): self {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind(string $abstract, mixed $concrete): void {
        $this->bindings[$abstract] = $concrete;
    }

    public function get(string $class): object {
        // If already bound, return it
        if (isset($this->bindings[$class])) {
            $bound = $this->bindings[$class];

            // If it's a closure, call it
            if (is_callable($bound)) {
                return $bound($this);
            }

            return $bound;
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class();
        }

        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $type = $param->getType();

            if (!$type || $type->isBuiltin()) {
                throw new Exception("Cannot resolve dependency: {$param->getName()}");
            }

            $dependencyClass = $type->getName();
            $dependencies[] = $this->get($dependencyClass);
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
