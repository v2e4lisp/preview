<?php

namespace Preview\Core;

use Preview\Preview;

/**
 * TestShared class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */
class TestShared {
    /**
     * name of the shared test
     *
     * @var string $name
     */
    protected $name;

    /**
     * Shared test body
     *
     * @var function $fn
     */
    protected $fn;

    /**
     * Constructor
     *
     * @param string $name
     * @param function $fn
     */
    public function __construct($name, $fn) {
        $this->fn = $fn;
        $this->name = $name;
        if (!Preview::php_version_is_53()) {
            $ref = new \ReflectionFunction($fn);
            $this->fn = $ref->getClosure();
        }
    }

    /**
     * Get name
     *
     * @param null
     * @retrun string
     */
    public function name() {
        return $this->name;
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