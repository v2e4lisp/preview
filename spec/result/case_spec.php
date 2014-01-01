<?php

namespace Preview\DSL\BDD;

// use Preview\Result\TestCase;
use Preview\Preview;
use Preview\Configuration;
use Preview\Core\TestSuite;
use Preview\Core\TestCase;
use Preview\Reporter\Base as BaseReporter;

require_once __DIR__.'/../helper.php';

describe("Result\TestCase", function () {
    before(function () {
        $this->title = "case-title";
        $this->parent_title = "suite-title";

        $this->startline = __LINE__ + 1;
        $testcase = new TestCase("case-title", function () {});
        $this->endline = __LINE__ - 1;
        $testcase->set_parent(new TestSuite("suite-title",
                                            function() {}));
        $this->result = $testcase->result;
    });

    it_behaves_like("default test result");

    describe("#filename", function () {
        it("should return current file name", function () {
            print_r($this->result->filename());
            ok($this->result->filename() == __FILE__);
        });
    });

    context("for skipped testcase", function () {
        before(function () {
            $testcase = new TestCase("case-title", function () {});
            $testcase->skip();
            $this->result = $testcase->result;
        });

        it_behaves_like("skipped test result");
    });

    context("for pending testcase", function () {
        before(function () {
            $testcase = new TestCase("case-title", null);
            $this->result = $testcase->result;
        });

        it_behaves_like("pending test result");
    });

    context("for finished testcase", function () {
        before_each(function () {
            $old_config = Preview::$config;
            Preview::$config = new Configuration;
            Preview::$config->reporter = new BaseReporter;

            $testcase = new TestCase("--case-title", function () {});
            $testcase->set_parent(new TestSuite("suite-title",
                                                function() {}));
            $testcase->run();

            Preview::$config = $old_config;
            $this->testcase = $testcase;
            $this->result = $testcase->result;
        });

        it_behaves_like("finished test result");

        context("when passed", function () {
            it_behaves_like("passed test result");
        });

        context("when failed", function () {
            before_each(function () {
                $this->testcase->set_failure(new \Exception("failed"));
            });

            it_behaves_like("failed test result");
        });

        context("when error occurred", function () {
            before_each(function () {
                $this->testcase->set_error(new \ErrorException("failed"));
            });

            it_behaves_like("error test result");
        });
    });

    context("for grouped test", function () {
        before(function () {
            $testcase = new TestCase("case-title", function () {});
            $testcase->set_parent(new TestSuite("suite-title",
                                                function() {}));

            $testcase->add_to_group("group-1");
            $testcase->add_to_group("group-2");

            $this->result = $testcase->result;
            $this->groups = array("group-1", "group-2");
        });

        it_behaves_like("grouped test result");
    });
});