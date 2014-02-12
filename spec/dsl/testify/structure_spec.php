<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\Testify\Suite as Suite;

require_once __DIR__.'/../../helper.php';

describe("testify[test structure]", function () {
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

    context("sample test suite", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            // ------ test -------
            try {
                $this->suite
                    ->test("c1", function () {})
                    ->test("c2", "skip", function () {})
                    ->test("c3");

                $child = new Suite("child suite");
                $child
                    ->test("c4", function () {})
                    ->test("c5", "skip", function () {})
                    ->test("c6");

                $this->suite
                    ->add_child($child)
                    ->load();

                $this->results = $this->world->run();
                // ------ end test -------
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
                // end new env
            }

            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }
        });

        it("should have one child suite", function () {
            ok(count($this->results) == 1);
        });

        it("should have 3 test cases", function ()  {
            ok(count($this->results[0]->cases()) == 3);
        });

        it("child suite should contain 3 cases", function () {
            $suites = $this->results[0]->suites();
            ok(count($suites[0]->cases()) == 3);
        });

        it("child suite should contain no suite", function () {
            $suites = $this->results[0]->suites();
            ok(count($suites[0]->suites()) == 0);
        });

        describe("reporter", function () {
            describe("#before_all", function () {
                it("should be called once", function () {
                    ok($this->config->reporter->before_all == 1);
                });
            });

            describe("#after_all", function () {
                it("should be called once", function () {
                    ok($this->config->reporter->after_all == 1);
                });
            });

            describe("#before_suite", function () {
                it("should be called twice", function () {
                    ok($this->config->reporter->before_suite == 2);
                });
            });

            describe("#after_suite", function () {
                it("should be called twice", function () {
                    ok($this->config->reporter->after_suite == 2);
                });
            });

            describe("#before_case", function () {
                it("should be called 6 times", function () {
                    ok($this->config->reporter->before_case == 6);
                });
            });

            describe("#after_case", function () {
                it("should be called 6 times", function () {
                    ok($this->config->reporter->after_case == 6);
                });
            });
        });
    });
});
