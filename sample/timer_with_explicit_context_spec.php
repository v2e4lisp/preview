<?php

namespace Preview\DSL\BDD;

use Preview\Timer;

// require_once 'ok.php';
require_once __DIR__ . '/../test/ok.php';

describe("Timer", function () {
    before_each(function($self) {
        $self->timer = new Timer;
    });

    context("when created", function() {
        it("should not be running", function($self) {
            ok($self->timer->running() === false);
        });

        it("should not be started", function($self) {
            ok($self->timer->started() === false);
        });

        it("should not be stopped", function($self) {
            ok($self->timer->stopped() === false);
        });

        it("should not have time span", function($self) {
            ok(!$self->timer->span());
        });

        it("cannot be stopped", function($self) {
            $self->timer->stop();
            ok($self->timer->stopped() === false);
        });
    });

    context("when started", function() {
        before_each(function($self) {
            $self->timer->start();
        });

        it("should be running", function($self) {
            ok($self->timer->running());
        });

        it("should be started", function($self) {
            ok($self->timer->started());
        });

        it("should not be stopped", function($self) {
            ok($self->timer->stopped() === false);
        });

        it("should not have time span", function($self) {
            ok(!$self->timer->span());
        });

        context("when stoped", function() {
            before_each(function($self) {
                $self->timer->stop();
            });

            it("should be running", function($self) {
                ok($self->timer->running() === false);
            });

            it("should be started", function($self) {
                ok($self->timer->started());
            });

            it("should not be stopped", function($self) {
                ok($self->timer->stopped());
            });

            it("should not have time span", function($self) {
                ok($self->timer->span());
            });
        });
    });
});
