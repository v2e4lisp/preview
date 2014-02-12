<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\qunit;

require_once __DIR__.'/../../helper.php';

describe("qunit[hook]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("#setup", function () {
        it("should run before each test", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;
            try {
                $message = array();
                qunit\suite("sample suite");

                qunit\setup(function () use (&$message) {
                    $message[] = "setup first";
                });

                qunit\test(function () use (&$message) {
                    $message[] = "then test";
                });

                qunit\test(function () use (&$message) {
                    $message[] = "then test";
                });
                $this->world->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
                // end new env
            }

            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }

            ok($message == array("setup first", "then test",
                                 "setup first", "then test"));

        });
    });

    describe("#teardown", function () {
        it("should run after each test", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            try {
                $message = array();
                qunit\suite("sample suite");

                qunit\teardown(function () use (&$message) {
                    $message[] = "then teardown";
                });

                qunit\test(function () use (&$message) {
                    $message[] = "test first";
                });

                qunit\test(function () use (&$message) {
                    $message[] = "test first";
                });
                $this->world->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
                // end new env
            }

            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }

            ok($message == array("test first", "then teardown",
                                 "test first", "then teardown"));
        });
    });
});