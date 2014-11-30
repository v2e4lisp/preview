<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\Configuration;
use Preview\Runner;
use Preview\Core\TestSuite;
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
            $test1 = new TestSuite("test-1", function () {});
            $test2 = new TestSuite("test-2", function () {});
            $this->runner = new Runner(array($test1, $test2));
        });

        it("should run tests", function () {
            Preview::$config = $this->new_config;
            $results = $this->runner->run();
            Preview::$config = $this->old_config;

            ok(count($results) == 2);
            ok($results[0]->finished() and $results[1]->finished());
        });
    });
});
