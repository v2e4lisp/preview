<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\Configuration;
use Preview\Runner;
use Preview\Reporter\Base as BaseReporter;

require_once 'helper.php';


describe("Runner", function () {
    describe("#run", function () {
        before_each(function () {
            $this->old_config = Preview::$config;
        });

        before_each(function () {
            $this->new_config = new Configuration;
            $this->new_config->reporter = new BaseReporter;
        });

        before_each(function () {
            $test1 = new \FakeTest("test-1");
            $test2 = new \FakeTest("test-2");
            $this->runner = new Runner(array($test1, $test2));
        });

        it("should run tests", function () {
            Preview::$config = $this->new_config;
            $results = $this->runner->run();
            Preview::$config = $this->old_config;

            ok(count($results) == 2);
            ok($results[0]->run and $results[1]->run);
        });
    });
});