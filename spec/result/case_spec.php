<?php

namespace Preview\DSL\BDD;

// use Preview\Result\TestCase;
use Preview\Core\TestSuite;
use Preview\Core\TestCase;

require_once __DIR__.'/../helper.php';

describe("TestCase", function () {
    before(function () {
        $this->startline = __LINE__ + 1;
        $testcase = new TestCase("case-title", function () {
        });
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

        describe("#time", function () {
            it("should return empty array", function () {
                ok($this->result->groups() == array());
            });
        });
    });
});