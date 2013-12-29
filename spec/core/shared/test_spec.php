<?php

namespace Preview\DSL\BDD;

require_once __DIR__."/../../helper.php";

shared_example("pending test", function () {
    describe("#runnable", function () {
        it ("should return false", function () {
            ok(!$this->subject->runnable());
        });
    });

    describe("#skip", function () {
        before_each(function () {
            $this->subject->skip();
        });

        it("should not work", function () {
            ok(!$this->subject->skipped);
        });
    });

    describe("#time", function () {
        it("should return null", function () {
            ok(is_null($this->subject->time()));
        });
    });
});

shared_example("skipped test", function () {
    describe("#runnable", function () {
        it ("should return false", function () {
            ok(!$this->subject->runnable());
        });
    });

    describe("#time", function () {
        it("should return null", function () {
            ok(is_null($this->subject->time()));
        });
    });
});

shared_example("finished test", function () {
    describe("#runnable", function () {
        it ("should return false", function () {
            ok(!$this->subject->runnable());
        });
    });

    describe("#time", function () {
        it("should return numeric value", function () {
            ok(is_numeric($this->subject->time()));
        });
    });
});