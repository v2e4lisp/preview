<?php
/*
 * How to use suite_setup.
 */

namespace Preview\DSL\TDD;

require_once __DIR__.'/../ok.php';

suite("suite_setup", function () {
    suite_setup(function () {
        $this->usage = "run suite_setup the current test suite";
    });

    suite_setup(function () {
        $this->note_1 = "suite_setup hooks are run in order";
    });

    suite_setup(function () {
        $this->ref = new \stdClass;
        $this->ref->name = "wenjun.yan";
        $this->value = "string";
    });

    test("can access the variable set in suite_setup hooks", function () {
        ok($this->note_1);
        ok($this->note_2);

        ok($this->value);
        ok($this->ref->name);

        $this->value = null;
        $this->ref->name = null;
    });

    test("suite_setup hooks run only once in current test suite", function () {
        /*
         * run tests in order, this will pass.
         */

        ok($this->value); // string is passed by value
        ok(empty($this->ref->name)); // object is passed by "ref".
    });

    suite_setup(function () {
        $this->note_2 = "wherever you put the suite_setup each hook, ".
            "it will run suite_setup this suite";
    });
});
