<?php

namespace Preview\DSL\BDD;

use Preview\Timer;

require_once 'ok.php';

describe("timer", function () {
    context("for new timer", function () {
        it("should not be running", function () {
            $timer = new Timer;
            ok($timer->running() === false);
        });

        it("should not be started", function () {
            $timer = new Timer;
            ok($timer->started() === false);
        });

        it("should not be stopped", function () {
            $timer = new Timer;
            ok($timer->stopped() === false);
        });
    });
});