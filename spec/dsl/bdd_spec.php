<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\Reporter\Base as BaseReporter;

require_once dirname(__DIR__).'/helper.php';

describe("bdd", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new BaseReporter;
    });

    context("when no test groups specified", function () {
        it("should run all the tests", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // below is our test
            describe("sample suite", function () {
                it("sample test 1", function () {});
                it("sample test 2", function () {});
            });
            $results = $this->world->run();

            // end new env
            // and go back to our normal test env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            // assert
            ok(count($results) == 1);
            ok(count($results[0]->all_cases()) == 2);
        });
    });
});