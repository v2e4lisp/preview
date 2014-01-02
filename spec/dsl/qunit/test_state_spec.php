<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\qunit;

require_once __DIR__.'/../../helper.php';

describe("qunit[test state]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("sample", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // below is our test
            qunit\suite("sample suite");

            qunit\test("error", function () {
                $a->value();
            });
            qunit\test("failed", function () {
                ok(false);
            });
            qunit\test("passed", function () {});
            qunit\test(function () {});
            qunit\test("pending");
            qunit\test("skipped", function () {})->skip();
            qunit\test("pending cannot skipped")->skip();

            $this->results = $this->world->run();

            // end new env
            // and go back to our normal test env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;
        });

        it("should have 1 testsuite result and 7 testcase", function () {
            ok(count($this->results) == 1);
            ok(count($this->results[0]->all_cases()) == 7);
        });

        it("should have one error test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->error();
            });
            ok(count($result) == 1);
        });

        it("should have one failure test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->failed();
            });
            ok(count($result) == 1);
        });

        it("should have two passed test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->passed();
            });
            ok(count($result) == 2);
        });

        it("should have one skipped test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->skipped();
            });
            ok(count($result) == 1);
        });

        it("should have two pending test results", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->pending();
            });
            ok(count($result) == 2);
        });
    });
});
