<?php
namespace Preview\DSL\Qunit;

require_once __DIR__.'/../ok.php';

suite("teardown");

teardown(function () {
    $this->usage = "this will function be called after each test case";
});

teardown(function () {
    $this->note_1 = "you can have multiple teardown";
});

teardown(function () {
    $this->note_2 = "teardown functions are run in order";
});

test("Run a test", function () {
    ok(true);
});

test("Run a another test", function () {
    ok(true);
});

teardown(function () {
    $this->note_3 = "It dosen't even matter where you put teardown function";
});
