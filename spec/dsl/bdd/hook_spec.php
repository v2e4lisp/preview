<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;

require_once __DIR__.'/../../helper.php';

describe("bdd[hook]", function () {
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
        it("should run before test at first", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            $message = array();
            describe("sample", function () use (&$message) {
                before_each(function () use (&$message) {
                    $message[] = "before_each first";
                });
                it("add a new message", function () use (&$message) {
                    $message[] = "then it";
                });
            });

            $this->world->run();

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok($message == array("before_each first", "then it"));
        });
    });

    describe("#after_each", function () {
        it("should run after each at last", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            $message = array();
            describe("sample", function () use (&$message) {
                after_each(function () use (&$message) {
                    $message[] = "then after_each";
                });
                it("add a new message", function () use (&$message) {
                    $message[] = "it first";
                });
            });
            $this->world->run();

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok($message == array("it first", "then after_each"));
        });
    });

    describe("#before", function () {
        it("should run before the testsuite", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            $message = array();
            $total = 0;
            describe("sample", function () use (&$message, &$total) {
                before(function () use (&$message, &$total) {
                    $message[] = "before";
                    $total++;
                });

                before_each(function () use (&$message) {
                    $message[] = "before_each first";
                });

                it("add a new message", function () use (&$message) {
                    $message[] = "then it";
                });

                it("add a new message", function () use (&$message) {
                    $message[] = "then it";
                });
            });

            $this->world->run();

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok($message[0] == "before");
            ok($total == 1);
        });
    });


    describe("#after", function () {
        it("should run after the testsuite", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            $message = array();
            $total = 0;

            describe("sample", function () use (&$message, &$total) {
                after(function () use (&$message, &$total) {
                    $message[] = "after";
                    $total++;
                });

                after_each(function () use (&$message) {
                    $message[] = "then after_each";
                });

                it("add a new message", function () use (&$message) {
                    $message[] = "it first";
                });

                it("add a new message", function () use (&$message) {
                    $message[] = "it first";
                });
            });

            $this->world->run();

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok(end($message) == "after");
            ok($total == 1);
        });
    });
});