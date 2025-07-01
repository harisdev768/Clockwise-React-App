<?php
// config/Container.php
namespace App\Config;

use ReflectionClass;
use ReflectionParameter;
use Exception;

class Container {
    public static ?Container $instance = null;

    public static function getInstance(): Container {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected array $instances = [];
    protected array $bindings = []; // <--- NEW

    // 🔗 Bind class or interface to concrete instance or class
    public function bind(string $abstract, $concrete): void {
        $this->bindings[$abstract] = $concrete;
    }

    // 🧠 Resolve any class
    public function get(string $class) {
        // Use bindings first
        if (isset($this->bindings[$class])) {
            $concrete = $this->bindings[$class];
            // If it's a class name, resolve it
            if (is_string($concrete)) {
                return $this->get($concrete);
            }
            // Otherwise return pre-built instance
            return $concrete;
        }

        // Return shared instance if exists
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        // Reflection-based resolution
        $reflector = new ReflectionClass($class);
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            $instance = new $class();
            $this->instances[$class] = $instance;
            return $instance;
        }

        // Resolve dependencies
        $params = $constructor->getParameters();
        $dependencies = array_map(function (ReflectionParameter $param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin()) {
                return $this->get($type->getName());
            }
            throw new Exception("Cannot resolve parameter \${$param->getName()}");
        }, $params);

        $instance = $reflector->newInstanceArgs($dependencies);
        $this->instances[$class] = $instance;
        return $instance;
    }
}
