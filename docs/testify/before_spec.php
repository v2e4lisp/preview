<?php
/*
 * How to use before hooks.
 */

namespace Preview\DSL\Testify;

require_once __DIR__.'/../ok.php';

$suite = new Suite("A sample test suite");

$suite->before(function () {
    $this->usage = "run before current test suite";
})->before(function () {
    $this->note_1 = "can have multiple before hooks";
})->before(function () {
    $this->note_2 = "before hooks are run in order";
})->before(function () {
    $this->ref = new \stdClass;
    $this->ref->name = "wenjun.yan";
    $this->value = "string";
});

$suite->test("It can access vars defined in before hook", function () {
    ok($this->note_1);
    ok($this->note_2);
    ok($this->note_3);

    ok($this->ref->name);
    ok($this->value);

    $this->value = null;
    $this->ref->name = null;

})->test("before hooks are run only once", function () {
    // $this-value and $this->ref are reassigned.
    ok($this->value); // string is passed by value
    ok(empty($this->ref->name)); // object is passed by "ref".
});

$suite->before(function () {
    $this->note_3 = "wherever you put the before hook, ".
        "it will run before this suite";
});

// load this test suite.
$suite->load();
