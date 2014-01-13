<?php
namespace Preview\DSL\TDD;

require_once __DIR__.'/../ok.php';

suite("setup", function () {
    setup(function () {
        $this->usage = "run before each test case";
    });

    setup(function () {
        $this->note_1 = "setup hooks are run in order";
    });

    setup(function () {
        $this->ref = new \stdClass;
        $this->ref->name = "wenjun.yan";
        $this->value = "string";
    });

    test("can access the variable set in setup hooks", function () {
        ok($this->note_1);
        ok($this->note_2);
        ok($this->value);
        ok($this->ref->name);

        $this->value = null;
        $this->ref->name = null;
    });

    test("setup hooks are run before each test case", function () {
        // $this-value and $this->ref are reassigned.
        ok($this->value); // string is passed by value
        ok($this->ref->name); // object is passed by "ref".
    });

    setup(function () {
        $this->note_2 = "wherever you put the before each hook, ".
            "it will run before each test case";
    });
});
