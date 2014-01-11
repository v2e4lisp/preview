<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../ok.php';

describe("An test suite.", function () {
    it("Run a test case", function () {
        ok(true);
    });

    describe("Nested test suite.", function () {
        it("Run a test case", function () {
            ok(true);
        });
    });

    context("An alias of describe.", function () {
        it("Run an another test case", function () {
            ok(true);
        });
    });
});