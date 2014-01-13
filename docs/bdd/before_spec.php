<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../ok.php';

describe("before", function () {
    before(function () {
        $this->usage = "run before the current test suite";
    });

    before(function () {
        $this->note_1 = "before hooks are run in order";
    });

    before(function () {
        $this->ref = new \stdClass;
        $this->ref->name = "wenjun.yan";
        $this->value = "string";
    });

    it("can access the variable set in before hooks", function () {
        ok($this->note_1);
        ok($this->note_2);

        ok($this->value);
        ok($this->ref->name);

        $this->value = null;
        $this->ref->name = null;
    });

    it("before hooks run only once in current test suite", function () {
        /*
         * run tests in order, this will pass.
         */

        ok($this->value); // string is passed by value
        ok(empty($this->ref->name)); // object is passed by "ref".
    });

    before(function () {
        $this->note_2 = "wherever you put the before each hook, ".
            "it will run before this suite";
    });
});
