<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;

require_once __DIR__.'/../../helper.php';

describe("bdd[shared example]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("#shared_example", function () {
        it("should create a shared example", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            shared_example("sample shared", function() {});

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok($this->world->shared_example("sample shared"));
        });
    });

    describe("#it_behaves_like", function () {
        it("should invoke a shared context", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            $flag = false;
            shared_example("sample shared", function() use (&$flag) {
                $flag = true;
            });

            describe("sample suite", function () {
                it_behaves_like("sample shared");
            });

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            ok($flag);
        });
    });

    it("should add new shared test to current suite", function () {
        // start new env
        Preview::$world = $this->world;
        Preview::$config = $this->config;

        shared_example("sample shared", function() {
            it("shared case", function () {});
        });

        describe("sample suite", function () {
            it_behaves_like("sample shared");
        });
        $results = Preview::$world->run();

        // end new env
        Preview::$world = $this->test_world;
        Preview::$config = $this->test_config;

        ok(count($results[0]->cases()) == 1);
    });

    it("should use current context", function () {
        // start new env
        Preview::$world = $this->world;
        Preview::$config = $this->config;

        shared_example("sample shared", function() {
            it("shared case", function () {
                ok($this->suite == "wow");
            });
        });

        describe("sample suite", function () {
            before(function () {
                $this->suite = "wow";
            });

            describe("sample suite 2", function () {
                it_behaves_like("sample shared");
            });
        });
        $results = Preview::$world->run();

        // end new env
        Preview::$world = $this->test_world;
        Preview::$config = $this->test_config;

        ok($results[0]->passed());
    });
});
