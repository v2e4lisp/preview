<?php

namespace Preview\Core;

use Preview\Preview;

class TestShared {
    public static $registered = array();

    public static function invoke($name, $args=array()) {
        if (!array_key_exists($name, self::$registered)) {
            throw new Exception("no such shared test: $name");
        }

        $shared = self::$registered[$name];
        $shared->setup($args);
    }

    public static function define($name, $fn) {
        self::$registered[$name] = new self($fn);
    }

    public function __construct($fn) {
        $this->fn = $fn;
        if (!Preview::php_version_is_53()) {
            $ref = new \ReflectionFunction($fn);
            $this->fn = $ref->getClosure();
        }
    }

    public function setup($args) {
        call_user_func_array($this->fn, $args);
    }
}