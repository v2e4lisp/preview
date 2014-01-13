<?php
/*
 * How to skip a test suite/case pending.
 */

namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite");

$suite->test("a skipped test case", "skip", function () {
    ok(true);
});

$yasuite = new Suite("skipped suite", "skip");

$yasuite->test("a test case", function () {
    ok(true);
});

// load this test suite.
$yasuite->load();
$suite->load();
