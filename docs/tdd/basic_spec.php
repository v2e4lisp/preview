<?php
/*
 * Basic usage
 */

namespace Preview\DSL\TDD;

require_once __DIR__.'/../ok.php';

suite("An test suite.", function () {
    test("Run a test case", function () {
        ok(true);
    });

    suite("Nested test suite.", function () {
        test("Run a test case", function () {
            ok(true);
        });
    });
});