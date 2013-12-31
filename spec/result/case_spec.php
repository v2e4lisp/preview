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
        $this->startline = __LINE__ + 1;
        $testcase = new TestCase("case-title", function () {});
        $this->endline = __LINE__ - 1;
        $testcase->set_parent(new TestSuite("suite-title",
                                            function() {}));
        $this->result = $testcase->result;
    });

    context("default", function () {
        describe("#title", function () {
            it("should return test title", function () {
                ok($this->result->title() == "case-title");
            });
        });

        describe("#title", function () {
            it("should return test title", function () {
                $titles = array("suite-title", "case-title");
                ok($this->result->titles() == $titles);
            });
        });

        describe("#full_title", function () {
            it("should return test full title", function () {
                ok($this->result->full_title() == "suite-title case-title");
            });
        });

        describe("#parent", function () {
            it("should return parent result", function () {
                ok($this->result->parent()->title() == "suite-title");
            });
        });

        describe("#filename", function () {
            it("should return current file name", function () {
                ok($this->result->filename() == __FILE__);
            });
        });

        describe("#startline", function () {
            it("should return start line number", function () {
                ok($this->result->startline() == $this->startline);
            });
        });

        describe("#endline", function () {
            it("should return end line number", function () {
                ok($this->result->endline() == $this->endline);
            });
        });

        describe("#passed", function () {
            it("should return false", function () {
                ok($this->result->passed() === false);
            });
        });

        describe("#error", function () {
            it("should return null", function () {
                ok(is_null($this->result->error()));
            });
        });

        describe("#failed", function () {
            it("should return null", function () {
                ok(is_null($this->result->failed()));
            });
        });

        describe("#error_or_failed", function () {
            it("should return null", function () {
                ok(is_null($this->result->error_or_failed()));
            });
        });

        describe("#skipped", function () {
            it("should return false", function () {
                ok($this->result->skipped() === false);
            });
        });

        describe("#pending", function () {
            it("should return false", function () {
                ok($this->result->pending() === false);
            });
        });

        describe("#skipped_or_pending", function () {
            it("should return false", function () {
                ok($this->result->skipped_or_pending() === false);
            });
        });

        describe("#finished", function () {
            it("should return false", function () {
                ok($this->result->finished() === false);
            });
        });

        describe("#runnable", function () {
            it("should return false", function () {
                ok($this->result->runnable() === true);
            });
        });

        describe("#time", function () {
            it("should return null", function () {
                ok(is_null($this->result->time()));
            });
        });

        describe("#groups", function () {
            it("should return empty array", function () {
                ok($this->result->groups() == array());
            });
        });
    });

    context("for skipped testcase", function () {
        before(function () {
            $testcase = new TestCase("case-title", function () {});
            $testcase->skip();
            $this->result = $testcase->result;
        });

        describe("#skipped", function () {
            it("should return true", function () {
                ok($this->result->skipped());
            });
        });

        describe("#skipped_or_pending", function () {
            it("should return true", function () {
                ok($this->result->skipped_or_pending());
            });
        });

        describe("#runnable", function () {
            it("should return false", function () {
                ok($this->result->runnable() === false);
            });
        });
    });

    context("for pending testcase", function () {
        before(function () {
            $testcase = new TestCase("case-title", null);
            $this->result = $testcase->result;
        });

        describe("#pending", function () {
            it("should return true", function () {
                ok($this->result->pending());
            });
        });

        describe("#skipped_or_pending", function () {
            it("should return true", function () {
                ok($this->result->skipped_or_pending());
            });
        });
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

        describe("#finished", function () {
            it("should return true", function () {
                ok($this->result->finished() === true);
                $x = $this->result->finished();
            });
        });

        describe("#runnable", function () {
            it("should return true", function () {
                ok(!$this->result->runnable());
            });
        });

        describe("#time", function () {
            it("should return an float", function () {
                ok(is_float($this->result->time()));
            });
        });

        context("when passed", function () {
            describe("#passed", function () {
                it("should return true", function () {
                    ok($this->result->passed() === true);
                })->group("passed");
            });
        });

        context("when failed", function () {
            before_each(function () {
                $this->testcase->set_failure(new \Exception("failed"));
            });

            describe("#failed", function () {
                it("should return failure exception", function () {
                    $e = $this->result->failed();
                    ok($e instanceof \Exception);
                });
            });

            describe("#failed_or_error", function () {
                it("should return failure exception", function () {
                    $e = $this->result->failed();
                    ok($e instanceof \Exception);
                });
            });
        })->group("failed");

        context("when error occurred", function () {
            before_each(function () {
                $this->testcase->set_error(new \ErrorException("failed"));
            });

            describe("#error", function () {
                it("should return error exception", function () {
                    $e = $this->result->error();
                    ok($e instanceof \ErrorException);
                });
            });

            describe("#failed_or_error", function () {
                it("should return failure exception", function () {
                    $e = $this->result->error();
                    ok($e instanceof \ErrorException);
                });
            });
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
        });

        describe("#groups", function () {
            it("should return array of groups", function () {
                ok($this->result->groups() == array("group-1", "group-2"));
            });
        });
    });
});