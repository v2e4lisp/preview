<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;

require_once __DIR__.'/../../helper.php';

describe("bdd[test state]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("suite state", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // below is our test
            describe("sample suite", function () {});
            describe("sample suite", function () {
                it("failed", function () {
                    ok(false);
                });
            });
            describe("sample suite", function () {
                it("error", function () {
                    $a->value;
                });
            });
            describe("sample suite", function () {})->skip();
            describe("sample suite");
            describe("sample suite")->skip();

            $this->results = $this->world->run();

            // end new env
            // and go back to our normal test env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;
        });

        it("sample should have 6 results", function () {
            ok(count($this->results) == 6);
        });
        
        it("sample should have 1 passed suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->passed();
            });
            ok(count($result) == 1);
        });

        it("sample should have 1 failed suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->failed();
            });
            ok(count($result) == 1);
        });

        it("sample should have 1 error suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->error();
            });
            ok(count($result) == 1);
        });
        
        it("sample should have 2 pending suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->pending();
            });
            ok(count($result) == 2);
        });

        it("sample should have 1 skipped suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->skipped();
            });
            ok(count($result) == 1);
        });
    });

    describe("case state", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // below is our test
            describe("sample suite", function () {
                it("error", function () {
                    $a->value;
                });

                it("failed", function () {
                    ok(false);
                });

                it("passed", function () {
                });

                it("pending");

                it("skipped", function () {})->skip();

                it("pending cannot skipped")->skip();
            });

            $this->results = $this->world->run();

            // end new env
            // and go back to our normal test env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;
        });
        
        it("sample should have 1 testsuite result and 6 testcase", function () {
            ok(count($this->results) == 1);
            ok(count($this->results[0]->all_cases()) == 6);
        });

        it("sample should have one error test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->error();
            });
            ok(count($result) == 1);
        });

        it("sample should have one failure test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->failed();
            });
            ok(count($result) == 1);
        });

        it("sample should have one passed test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->passed();
            });
            ok(count($result) == 1);
        });

        it("sample should have one skipped test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->skipped();
            });
            ok(count($result) == 1);
        });

        it("sample should have two pending test results", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->pending();
            });
            ok(count($result) == 2);
        });
    });
});
