<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\Testify\Suite as Suite;

require_once __DIR__.'/../../helper.php';

describe("bdd[test state]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("suite state", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            try {
                // below is our test
                $s1 = new Suite("sample suite1");
                $s2 = new Suite("sample suite2");
                $s2->test("failed", function () {
                    ok(false);
                });
                $s3 = new Suite("sample suite3");
                $s3->test("error", function () {
                    $a->value;
                });
                $s4 = new Suite("skipped sample", "skip");
                foreach(array($s1, $s2, $s3, $s4) as $s) {
                    $s->load();
                }
                $this->results = $this->world->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
                // end new env
            }

            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }
        });

        it("sample should have 4 results", function () {
            ok(count($this->results) == 4);
        });

        it("sample should have 1 passed suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->passed();
            });
            ok(count($result) == 1);
        });

        it("sample should have 1 failed suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->failed();
            });
            ok(count($result) == 1);
        });

        it("sample should have 1 error suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->error();
            });
            ok(count($result) == 1);
        });

        it("sample should have 1 skipped suite", function () {
            $all = $this->results;
            $result = array_filter($all, function ($suite) {
                return $suite->skipped();
            });
            ok(count($result) == 1);
        });
    });

    describe("case state", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $exception_in_sample_code = null;

            // below is our test
            try {
                $suite = new Suite("sample suite");
                $suite
                    ->test("error", function () {
                        $a->value();
                    })
                    ->test("failed", function () {
                        ok(false);
                    })
                    ->test("passed", function () {})
                    ->test("skipped", "skip", function () {})
                    ->load();
                $this->results = $this->world->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
                // end new env
            }

            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }
        });

        it("sample should have 1 testsuite result and 4 testcase", function () {
            ok(count($this->results) == 1);
            ok(count($this->results[0]->all_cases()) == 4);
        });

        it("sample should have one error test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->error();
            });
            ok(count($result) == 1);
        });

        it("sample should have one failure test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->failed();
            });
            ok(count($result) == 1);
        });

        it("sample should have one passed test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->passed();
            });
            ok(count($result) == 1);
        });

        it("sample should have one skipped test result", function () {
            $all = $this->results[0]->all_cases();
            $result = array_filter($all, function ($case) {
                return $case->skipped();
            });
            ok(count($result) == 1);
        });
    });
});
