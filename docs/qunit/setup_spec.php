<?php
namespace Preview\DSL\Qunit;

require_once __DIR__.'/../ok.php';

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

test("have access to vars defined in setups", function () {
    ok($this->note_1);
    ok($this->note_2);
    ok($this->note_3);
});

test("Run a another test", function () {
    ok(true);
});

setup(function () {
    $this->note_3 = "It dosen't even matter where you put setup function";
});
