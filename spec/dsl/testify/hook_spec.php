<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\Testify\Suite as Suite;

require_once __DIR__.'/../../helper.php';

describe("Testify", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("#before_each", function () {
        it("should run before each test", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // -- test start --
            $message = array();
            $suite = new Suite("sample suite");

            $suite->before_each(function () use (&$message) {
                $message[] = "setup first";
            });

            $suite->test(function () use (&$message) {
                $message[] = "then test";
            });

            $suite->test(function () use (&$message) {
                $message[] = "then test";
            });
            $suite->load();

            $this->world->run();
            // -- test end --

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok($message == array("setup first", "then test",
                                 "setup first", "then test"));
        });
    });

    describe("#after_each", function () {
        it("should run after each test", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // -- test start --
            $message = array();
            $suite = new Suite("sample suite");

            $suite->after_each(function () use (&$message) {
                $message[] = "then teardown";
            });

            $suite->test(function () use (&$message) {
                $message[] = "test first";
            });

            $suite->test(function () use (&$message) {
                $message[] = "test first";
            });

            $suite->load();
            $this->world->run();
            // -- test end --

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok($message == array("test first", "then teardown",
                                 "test first", "then teardown"));
        });
    });

    describe("#before", function () {
        it("should run before the testsuite", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // -- test start --
            $message = array();
            $total = 0;
            $suite = new Suite("sample suite");

            $suite->before(function () use (&$message, &$total) {
                $message[] = "before";
                $total++;
            });

            $suite->before_each(function () use (&$message) {
                $message[] = "before_each first";
            });

            $suite->test("add a new message", function () use (&$message) {
                $message[] = "then it";
            });

            $suite->test("add a new message", function () use (&$message) {
                $message[] = "then it";
            });

            $suite->load();
            $this->world->run();
            // -- test end --

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok($message[0] == "before");
            ok($total == 1);
        });
    });


    describe("#after_hook", function () {
        it("should run after the testsuite", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // -- test start --
            $message = array();
            $total = 0;
            $suite = new Suite("sample suite");

            $suite->after(function () use (&$message, &$total) {
                $message[] = "after";
                $total++;
            });

            $suite->after_each(function () use (&$message) {
                $message[] = "then after_each";
            });

            $suite->test("add a new message", function () use (&$message) {
                $message[] = "it first";
            });

            $suite->test("add a new message", function () use (&$message) {
                $message[] = "it first";
            });

            $suite->load();
            $this->world->run();
            // -- test end --

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok(end($message) == "after");
            ok($total == 1);
        });
    });
});