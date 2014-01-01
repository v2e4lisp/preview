<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\Reporter\Base as BaseReporter;

require_once __DIR__.'/../helper.php';

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

    describe("test structure", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // ------ test -------
            describe("s1", function () {
                it("c1", function () {});
                it("c2", function () {})->skip();
                it("c3");

                describe("s2", function () {
                    it("c4", function () {});
                    it("c5", function () {})->skip();
                    it("c6");
                });
            });
            $this->results = $this->world->run();
            // ------ end test -------

            // end new env
            // and go back to our normal test env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;
        });

        it("should have one child suite", function () {
            ok(count($this->results) == 1);
        });

        it("should have 3 test cases", function ()  {
            ok(count($this->results[0]->cases()) == 3);
        });

        it("child suite should contain 3 cases", function () {
            $suites = $this->results[0]->suites();
            ok(count($suites[0]->cases()) == 3);
        });

        it("child suite should contain no suite", function () {
            $suites = $this->results[0]->suites();
            ok(count($suites[0]->suites()) == 0);
        });

        describe("reporter hook", function () {
            describe("#before_all", function () {
                it("should be called once", function () {
                    ok($this->config->reporter->before_all == 1);
                });
            });

            describe("#after_all", function () {
                it("should be called once", function () {
                    ok($this->config->reporter->after_all == 1);
                });
            });

            describe("#before_suite", function () {
                it("should be called twice", function () {
                    ok($this->config->reporter->before_suite == 2);
                });
            });

            describe("#after_suite", function () {
                it("should be called twice", function () {
                    ok($this->config->reporter->after_suite == 2);
                });
            });

            describe("#before_case", function () {
                it("should be called 6 times", function () {
                    ok($this->config->reporter->before_case == 6);
                });
            });

            describe("#after_case", function () {
                it("should be called 6 times", function () {
                    ok($this->config->reporter->after_case == 6);
                });
            });
        });
    });

    describe("test case state", function () {
        before_each(function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            // below is our test
            describe("sample suite", function () {
                it("error", function () {
                    $a->value();
                });

                it("failed", function () {
                    ok(false);
                });

                it("passed", function () {
                });

                it("pending");

                it("skipped", function () {})->skip();

                it("pending cannot skipped")->skip();
            });

            $this->results = $this->world->run();

            // end new env
            // and go back to our normal test env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;
        });

        it("should have 1 testsuite result and 6 testcase", function () {
            ok(count($this->results) == 1);
            ok(count($this->results[0]->all_cases()) == 6);
        });

        it("should have one error test result", function () {
            ok(function () {
                $results = $this->results[0]->all_cases();
                foreach($results as $result) {
                    if($result->error()) {
                        return true;
                    }
                }
            });
        });

        it("should have one failure test result", function () {
            ok(function () {
                $results = $this->results[0]->all_cases();
                foreach($results as $result) {
                    if($result->failed()) {
                        return true;
                    }
                }
            });
        });

        it("should have one passed test result", function () {
            ok(function () {
                $results = $this->results[0]->all_cases();
                foreach($results as $result) {
                    if($result->passed()) {
                        return true;
                    }
                }
            });
        });

        it("should have one skipped test result", function () {
            ok(function () {
                $results = $this->results[0]->all_cases();
                foreach($results as $result) {
                    if($result->skipped()) {
                        return true;
                    }
                }
            });
        });

        it("should have two pending test results", function () {
            ok(function () {
                $total = 0;
                $results = $this->results[0]->all_cases();
                foreach($results as $result) {
                    if($result->pending()) {
                        $total++;
                    }
                }
                return $total == 2;
            });
        });
    });
});