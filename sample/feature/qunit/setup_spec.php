<?php
namespace Preview\DSL\Qunit;

require_once __DIR__.'/../../ok.php';

suite("setup");

setup(function () {
    $this->usage = "this will function be called before each test case";
});

setup(function () {
    $this->note_1 = "you can have multiple setup";
});

setup(function () {
    $this->note_2 = "setup functions are run in order";
});

test("Run a test", function () {
    ok(true);
});

test("Run a another test", function () {
    ok(true);
});

setup(function () {
    $this->note_3 = "It dosen't even matter where you put setup function";
});