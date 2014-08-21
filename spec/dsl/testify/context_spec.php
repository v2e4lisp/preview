<?php

namespace Preview\DSL\BDD;
use Preview\DSL\Testify\Suite as Suite;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;

require_once __DIR__.'/../../helper.php';

describe("testify[context]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    before_each(function () {
        $this->suite = new Suite("sample suite");
    });

    context("test suite", function () {
        it("share the context with before/after hook", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $tmp = null;
            $exception_in_sample_code = null;

            try {
                $this->suite->test("sample test", function () use (&$tmp) {
                    ok($this !== $tmp);
                })->before(function () use (&$tmp) {
                    $tmp = $this;
                })->after(function () use (&$tmp) {
                    ok($this === $tmp);
                })->load();
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

            $result = $results[0];
            ok($result->passed());
        });

        it("extend the context of its parent test suite", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            try {
                $child = new Suite("child suite");

                $this->suite
                    ->before(function () {
                        $this->user = "wenjun.yan";
                    })
                    ->add_child($child);

                $child
                    ->before(function () {
                        $this->shared = ($this->user == "wenjun.yan");
                    })->test("access parent suite context", function () {
                        ok($this->shared);
                    });

                $this->suite->load();

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

            $result = $results[0];
            ok($result->passed());
        });
    });

    context("test case", function (){
        it("share the context with before/after each hook", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $context_tmp = null;
            $exception_in_sample_code = null;

            try {
                $this->suite
                    ->before_each(function () use (&$context_tmp) {
                        $this->user = "wenjun.yan";
                        $context_tmp = $this;
                    })
                    ->test("should have a user", function () use (&$context_tmp) {
                        ok($this === $context_tmp);
                    })
                    ->after_each(function () use (&$context_tmp) {
                        ok($this === $context_tmp);
                    })
                    ->load();
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

            $suite_result = $results[0];
            $cases_result = $suite_result->cases();
            ok($suite_result->passed());
            ok($cases_result[0]->passed());
        });

        it("should extend parent test suite context", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;
            try {
                $this->suite
                    ->before(function () {
                        $this->user = "wenjun.yan";
                    })
                    ->before_each(function () {
                        ok($this->user == "wenjun.yan");
                    })
                    ->test("should have a user", function () {
                        ok($this->user == "wenjun.yan");
                    })
                    ->after_each(function () {
                        ok($this->user == "wenjun.yan");
                    })
                    ->load();
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

            $result = $results[0];
            ok($result->passed());
        });
    });
});

