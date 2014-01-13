<?php
/*
 * How to use before each hooks.
 */

namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite.");

$suite->before_each(function () {
    $this->usage = "run before each test case in current test suite";
})->before_each(function () {
    $this->note_1 = "can have multiple before each hooks";
})->before_each(function () {
    $this->note_2 = "before each hooks are run in order";
});

$suite->test("It can access vars defined in before each hook", function () {
    ok($this->note_1);
    ok($this->note_2);
    ok($this->note_3);
});

$suite->before_each(function () {
    $this->note_3 = "wherever you put the before each hook, ".
        "it will run before each test case";
});

// load this test suite.
$suite->load();
