<?php
/*
 * How to use after_each hooks.
 */

namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite");

$suite->after_each(function () {
    $this->usage = "run after each test case in current test suite";
})->after_each(function () {
    $this->note_1 = "can have multiple after_each hooks";
})->after_each(function () {
    $this->note_2 = "after_each hooks are run in order";
});

$suite->test("a sample test case", function () {
    ok(true);
});

$suite->after_each(function () {
    $this->note_3 = "wherever you put the after_each hook, ".
        "it will run after_each this suite";
});

// load this test suite.
$suite->load();

