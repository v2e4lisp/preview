<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;

require_once __DIR__.'/../../helper.php';

describe("bdd", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("context", function () {
        it("before_each and after_each ".
           "use the same context as test case", function () {
           // start new env
           Preview::$world = $this->world;
           Preview::$config = $this->config;
           $context_tmp = null;

           describe("context sample", function () use (&$context_tmp) {
               before_each(function () use (&$context_tmp) {
                   $this->user = "wenjun.yan";
                   $context_tmp = $this;
               });

               it("should have a user", function () use (&$context_tmp) {
                   ok($this === $context_tmp);
               });

               after_each(function () use (&$context_tmp) {
                   ok($this === $context_tmp);
               });
           });
           $results = Preview::$world->run();

           // end new env
           Preview::$world = $this->test_world;
           Preview::$config = $this->test_config;

           $suite_result = $results[0];
           $cases_result = $suite_result->cases();
           ok($suite_result->passed());
           ok($cases_result[0]->passed());
       });
    });
});