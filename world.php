<?php

require 'runner.php';

class World {
    public static $reporter = null;

    public static $start_points = array();

    public static $testsuite_chain = array();

    public static function current() {
        if (empty(self::$testsuite_chain)) {
            return null;
        }
        return end(self::$testsuite_chain);
    }

    public static function push($testsuite) {
        if (empty(self::$testsuite_chain)) {
            self::$start_points[] = $testsuite;
        }

        self::$testsuite_chain[] = $testsuite;
    }

    public static function pop() {
        return array_pop(self::$testsuite_chain);
    }

    public static function run() {
        $runner = new Runner(self::$start_points);
        return $runner->run();
    }
}