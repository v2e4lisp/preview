<?php

namespace Preview\DSL\BDD;

describe("tdd", function () {
    describe("#suite", function () {
        it("alias for bdd#describe");
    });

    describe("#test", function () {
        it("alias for bdd#it");
    });

    describe("#setup", function () {
        it("alias for bdd#before_each");
    });

    describe("#suite_setup", function () {
        it("alias for bdd#before");
    });

    describe("#teardown", function () {
        it("alias for bdd#after_each");
    });

    describe("#suite_teardown", function () {
        it("alias for bdd#after");
    });
});