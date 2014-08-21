<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\Export;

require_once __DIR__.'/../../helper.php';

describe("export[hook]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("before each", function () {
        it("should run before each test", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            try {
                $message = array();
                $suite = array(
                    "before each" => function () use (&$message) {
                        $message[] = "before each first";
                    },

                    "sample test" => function () use (&$message) {
                        $message[] = "then test";
                    },

                    "sample test 2" => function () use (&$message) {
                        $message[] = "then test";
                    },
                );
                Export\export("sample", $suite);
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

            ok($message == array("before each first", "then test",
                                 "before each first", "then test"));
        });
    });

    describe("#after each", function () {
        it("should run after each test", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            try {
                $message = array();
                $suite = array(
                    "after each" => function () use (&$message) {
                        $message[] = "then after each";
                    },

                    "sample test" => function () use (&$message) {
                        $message[] = "test first";
                    },

                    "sample test 2" => function () use (&$message) {
                        $message[] = "test first";
                    },
                );
                Export\export("sample suite", $suite);
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

            ok($message == array("test first", "then after each",
                                 "test first", "then after each"));
        });
    });

    describe("before", function () {
        it("should run before the testsuite", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;
            try {
                $message = array();
                $total = 0;
                $suite = array(
                    "before" => function () use (&$message, &$total) {
                        $message[] = "before";
                        $total++;
                    },

                    "before_each" => function () use (&$message) {
                        $message[] = "before_each first";
                    },

                    "add a new message" => function () use (&$message) {
                        $message[] = "then it";
                    },

                    "add a new message" => function () use (&$message) {
                        $message[] = "then it";
                    },
                );
                Export\export("sample suite", $suite);
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

            ok($message[0] == "before");
            ok($total == 1);
        });
    });


    describe("after", function () {
        it("should run after the testsuite", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            try {
                $message = array();
                $total = 0;

                $suite = array(
                    "after" => function () use (&$message, &$total) {
                        $message[] = "after";
                        $total++;
                    },

                    "after_each" => function () use (&$message) {
                        $message[] = "then after_each";
                    },

                    "add a new message" => function () use (&$message) {
                        $message[] = "it first";
                    },

                    "add a new message" => function () use (&$message) {
                        $message[] = "it first";
                    },
                );
                Export\export("sample suite", $suite);
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

            ok(end($message) == "after");
            ok($total == 1);
        });
    });
});