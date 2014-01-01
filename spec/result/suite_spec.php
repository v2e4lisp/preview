<?php

namespace Preview\DSL\BDD;

// use Preview\Result\TestCase;
use Preview\Preview;
use Preview\Configuration;
use Preview\Core\TestSuite;
use Preview\Core\TestCase;
use Preview\Reporter\Base as BaseReporter;

describe("Result\TestSuite", function () {
    before(function () {
        $this->title = "suite-title-1";
        $this->parent_title = "suite-title-2";

        $this->startline = __LINE__ + 1;
        $testsuite = new TestSuite("suite-title-1", function () {});
        $this->endline = __LINE__ - 1;
        $testsuite->set_parent(new TestSuite("suite-title-2",
                                            function() {}));
        $this->result = $testsuite->result;
    });

    it_behaves_like("default test result");

    describe("#filename", function () {
        it("should return current file name", function () {
            print_r($this->result->filename());
            ok($this->result->filename() == __FILE__);
        });
    });

    context("for skipped testsuite", function () {
        before(function () {
            $testsuite = new TestSuite("suite-title", function () {});
            $testsuite->skip();
            $this->result = $testsuite->result;
        });

        it_behaves_like("skipped test result");
    });


    context("for pending testsuite", function () {
        before(function () {
            $testsuite = new TestSuite("suite-title", null);
            $this->result = $testsuite->result;
        });

        it_behaves_like("pending test result");
    });

    context("for finished testsuite", function () {
        before_each(function () {
            $old_config = Preview::$config;
            Preview::$config = new Configuration;
            Preview::$config->reporter = new BaseReporter;

            $testsuite = new TestSuite("suite-title-1", function () {});
            $testsuite->set_parent(new TestSuite("suite-title-2",
                                                function() {}));
            $testsuite->run();

            Preview::$config = $old_config;
            $this->testsuite = $testsuite;
            $this->result = $testsuite->result;
        });

        it_behaves_like("finished test result");

        context("when passed", function () {
            it_behaves_like("passed test result");
        });

        context("when failed", function () {
            before_each(function () {
                $this->testsuite->set_failure(new \Exception("failed"));
            });

            it_behaves_like("failed test result");
        });

        context("when error occurred", function () {
            before_each(function () {
                $this->testsuite->set_error(new \ErrorException("failed"));
            });

            it_behaves_like("error test result");
        });
    });

    context("for grouped test", function () {
        before(function () {
            $testsuite = new Testsuite("case-title-1", function () {});
            $testsuite->set_parent(new TestSuite("suite-title-2",
                                                function() {}));

            $testsuite->add_to_group("group-1");
            $testsuite->add_to_group("group-2");

            $this->result = $testsuite->result;
            $this->groups = array("group-1", "group-2");
        });

        it_behaves_like("grouped test result");
    });

    describe("get cases and suites", function () {
        before_each(function () {
            $testsuite = new Testsuite("suite-title-1", function () {});
            $testsuite_parent = new TestSuite("suite-title-2",
                                              function() {});
            $testcase_1 = new TestCase("case-title-1", function (){});
            $testcase_2 = new TestCase("case-title-2", function (){});

            $testsuite->add($testcase_1);
            $testsuite_parent->add($testcase_2);
            $testsuite_parent->add($testsuite);
            $this->result = $testsuite_parent->result;
        });

        describe("#cases", function () {
            it("should return its direct children case results", function (){
                $cases = $this->result->cases();
                ok(count($cases) == 1);
                ok($cases[0]->title() == "case-title-2");
            });
        });

        describe("#suites", function () {
            it("should return its direct children suite results", function (){
                $suites = $this->result->suites();
                ok(count($suites) == 1);
                ok($suites[0]->title() == "suite-title-1");
            });
        });

        describe("#all_cases", function () {
            it("should return all of its children case results", function (){
                $cases = $this->result->all_cases();
                ok(count($cases) == 2);
            });
        });
    });
});