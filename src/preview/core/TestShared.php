<?php

namespace Preview\Core;

use Preview\Preview;

/**
 * TestShared class
 * This class is responsible to create common shared test.
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */
class TestShared {
    /**
     * Registered shared test
     *
     * @var array $registered an array of closure
     */
    public static $registered = array();

    /**
     * Invoke some shared test.
     *
     * @param string $name shared test name.
     * @param array $args array of arguments . Default empty array.
     * @retrun null
     */
    public static function invoke($name, $args=array()) {
        if (!array_key_exists($name, self::$registered)) {
            throw new Exception("no such shared test: $name");
        }

        $shared = self::$registered[$name];
        $shared->setup($args);
    }

    /**
     * Create a shared test
     *
     * @param string $name shared test name
     * @param function $fn
     * @retrun null
     */
    public static function define($name, $fn) {
        self::$registered[$name] = new self($fn);
    }

    /**
     * Constructor
     *
     * @param function $fn
     */
    public function __construct($fn) {
        $this->fn = $fn;
        if (!Preview::php_version_is_53()) {
            $ref = new \ReflectionFunction($fn);
            $this->fn = $ref->getClosure();
        }
    }

    /**
     * Setup the shared test.
     *
     * @param array $args
     * @retrun null
     */
    public function setup($args) {
        call_user_func_array($this->fn, $args);
    }
}