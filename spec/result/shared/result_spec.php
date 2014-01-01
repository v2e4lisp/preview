<?php

namespace Preview\DSL\BDD;

shared_example("default test result", function () {
    context("default", function () {
        describe("#title", function () {
            it("should return test title", function () {
                ok($this->result->title() == $this->title);
            });
        });

        describe("#title", function () {
            it("should return test title", function () {
                $titles = array($this->parent_title, $this->title);
                ok($this->result->titles() == $titles);
            });
        });

        describe("#full_title", function () {
            it("should return test full title", function () {
                ok($this->result->full_title() ==
                   "{$this->parent_title} {$this->title}");
            });
        });

        describe("#parent", function () {
            it("should return parent result", function () {
                ok($this->result->parent()->title() == $this->parent_title);
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
});

shared_example("skipped test result", function () {
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

shared_example("pending test result", function () {
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

shared_example("finished test result", function () {
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
});

shared_example("passed test result", function () {
    describe("#passed", function () {
        it("should return true", function () {
            ok($this->result->passed() === true);
        });
    });
});

shared_example("failed test result", function () {
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
});

shared_example("error test result", function () {
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

shared_example("grouped test result", function () {
    describe("#groups", function () {
        it("should return array of groups", function () {
            ok($this->result->groups() == $this->groups);
        });
    });
});