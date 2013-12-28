<?php

namespace Preview\Core;

use Preview\Timer;
use Preview\Preview;

class TestShared extends TestSuite {
    public static $registered = array();

    public static function invoke($name) {
        if (!array_key_exists($name, static::$registered)) {
            throw new Exception("no such shared test: $name");
        }

        $shared = self::$registered[$name];
        $shared->setup();
    }

    public static function define($name, $fn) {
        static::$registered[$name] = new TestSuite("", $fn);
    }

    public function run() {
        if (!$this->runnable()) {
            return;
        }

        $this->extend_context_with_parent();
        $this->run_before();
        foreach ($this->cases as $case) {
            $case->run();
        }
        foreach ($this->suites as $suite) {
            $suite->run();
        }
        $this->run_after();
        $this->finish();
    }
}