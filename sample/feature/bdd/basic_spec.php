<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../../ok.php';

describe("An test suite.", function () {
    it("run a test case", function () {
        ok(true);
    });

    describe("nested test suite", function () {
        it("run a test case", function () {
            ok(true);
        });
    });

    context("An alias of describe.", function () {
        it("run an another test case", function () {
            ok(true);
        });
    });
});