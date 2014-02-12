<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\Export;

require_once __DIR__.'/../../helper.php';

describe("export[syntax]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("#export", function () {
        it("accept 2 params(title and options)", function () {
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            try {
                Export\export("title", array());
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
            }

            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }

        });

        it("accept 1 param which is an array containing all tests", function () {
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            $exception_in_sample_code = null;

            try {
                Export\export(array());
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
            }

            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }
        });
    });

    describe("test array", function () {
        it("before/after before each, after each are keys for hooks",function () {
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            try {
                Export\export("title", array(
                                  "before" => function () {},
                                  "before each" => function () {},
                                  "after" => function () {},
                                  "after each" => function () {},
                                  "this one is test" => function () {}
                              ));
                $results = Preview::$world->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
                // end new env
            }

            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }

            ok(count($results[0]->cases()) == 1);
        });

        it("value should be \Closure");
    });
});