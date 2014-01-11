<?php
namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite");

$suite->before(function () {
    $this->usage = "run before current test suite";
})->before(function () {
    $this->note_1 = "can have multiple before hooks";
})->before(function () {
    $this->note_2 = "before hooks are run in order";
});

$suite->test("It can access vars defined in before hook", function () {
    ok($this->usage == "run before current test suite");
    ok($this->note_3 == "wherever you put the before hook, ".
       "it will run before this suite");
})->test("before hooks are run only once", function () {
    ok(true);
});

$suite->before(function () {
    $this->note_3 = "wherever you put the before hook, ".
        "it will run before this suite";
});

// load this test suite.
$suite->load();