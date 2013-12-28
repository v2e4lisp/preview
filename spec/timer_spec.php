<?php

namespace Preview\DSL\BDD;

use Preview\Timer;

require_once 'ok.php';

describe("Timer", function () {
    before_each(function () {
        $this->timer = new Timer;
    });

    context("when created", function () {
        it("should not be running", function () {
            ok($this->timer->running() === false);
        });

        it("should not be started", function () {
            ok($this->timer->started() === false);
        });

        it("should not be stopped", function () {
            ok($this->timer->stopped() === false);
        });

        it("should not have time span", function () {
            ok(!$this->timer->span());
        });

        it("cannot be stopped", function () {
            $this->timer->stop();
            ok($this->timer->stopped() === false);
        });
    })->group("create");

    context("when started", function () {
        before_each(function () {
            $this->timer->start();
        });

        it("should be running", function () {
            ok($this->timer->running());
        });

        it("should be started", function () {
            ok($this->timer->started());
        });

        it("should not be stopped", function () {
            ok($this->timer->stopped() === false);
        });

        it("should not have time span", function () {
            ok(!$this->timer->span());
        });

        context("then stopped", function () {
            before_each(function() {
                $this->timer->stop();
            });

            it("should not be running", function () {
                ok($this->timer->running() === false);
            });

            it("should be started", function () {
                ok($this->timer->started());
            });

            it("should be stopped", function () {
                ok($this->timer->stopped());
            });

            it("should have time span", function () {
                ok($this->timer->span());
            });
        })->group("stop");
    })->group("start");
});
