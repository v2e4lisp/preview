<?php
/*
 * How to make a test suite/case pending.
 */

namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite");

$suite->test("a test case", function () {
    ok(true);
});

$suite->test("a pending test case");

// load this test suite.
$suite->load();
