<?php
/*
 * How to use after hooks.
 */

namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite");

$suite->after(function () {
    $this->usage = "run after current test suite";
})->after(function () {
    $this->note_1 = "can have multiple after hooks";
})->after(function () {
    $this->note_2 = "after hooks are run in order";
});

$suite->test("a sample test case", function () {
    ok(true);
});

$suite->after(function () {
    $this->note_3 = "wherever you put the after hook, ".
        "it will run after this suite";
});

// load this test suite.
$suite->load();
