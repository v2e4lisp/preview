<?php
namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite");

$suite->test("a test case", function () {
    ok(true);
})->test(function () {
    // a test case with no description;
    ok(true);
});

// load this test suite.
$suite->load();