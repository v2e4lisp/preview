<?php
/*
 * How to group test in testify.
 */

namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite1 = new Suite("A grouped test suite", "group-2");

$suite1->test("a grouped and test case", 
    "group-1", "group-2", function () {
        ok(true);
    });

$suite1->test("a grouped and skipped test case",
    "skip", "group-1", function () {
        ok(true);
    });

$suite2 = new Suite("grouped suite", "group-1", "group-2");

$suite2->test("a test case", function () {
    ok(true);
});

$suite3 = new Suite("grouped and skipped test suite", 
    "group-1", "skip", "group-2");

$suite3->test("a test case", function () {
    ok(true);
});

// load this test suite.
$suite1->load();
$suite2->load();
$suite3->load();
