<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../../ok.php';

describe("An sample test suite", function () {
    before_each(function () {
        $this->usage = "run before each test case";
    });

    before_each(function () {
        $this->note = "before_each hooks are run in order";
    });

    before_each(function () {
        $this->ref = new \stdClass;
        $this->ref->name = "wenjun.yan";
        $this->value = "string";
    });

    it("can access the variable set in before_each hooks", function () {
        ok($this->usage);
        ok($this->note);
        ok($this->value);
        ok($this->ref->name);

        $this->value = null;
        $this->ref->name = null;
    });

    it("before_each hooks are run before current test", function () {
        // $this-value and $this->ref are reassigned.
        ok($this->value); // string is passed by value
        ok($this->ref->name); // object is passed by "ref".
    });
});