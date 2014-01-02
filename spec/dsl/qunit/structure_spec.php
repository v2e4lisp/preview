<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\qunit;

require_once __DIR__.'/../../helper.php';

describe("qunit[test structure]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    context("sample test suite", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // ------ test -------
            qunit\suite("s1");
            qunit\test("c1", function () {});
            qunit\test("c2", function () {})->skip();
            qunit\test("c3");

            qunit\suite("s2");
            qunit\test(function () {});
            qunit\test("c5", function () {})->skip();
            qunit\test("c6");
            $this->results = $this->world->run();
            // ------ end test -------

            // end new env
            // and go back to our normal test env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;
        });

        it("should have 2 suite", function () {
            ok(count($this->results) == 2);
        });

        it("first suite should have 3 test cases", function ()  {
            ok(count($this->results[0]->cases()) == 3);
        });

        it("second suite should have 3 test cases", function () {
            ok(count($this->results[1]->cases()) == 3);
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
