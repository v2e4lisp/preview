<?php
/*
 * Basic usage.
 */

namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite");

$suite->test("a test case", function () {
    ok(true);
})->test(function () {
    // a test case with no description;
    ok(true);
});

$child = new Suite("Child test suite");

$child->test("test case in child test suite", function () {
    ok(true);
});

$suite->add_child($child);

// load this test suite.
$suite->load();
