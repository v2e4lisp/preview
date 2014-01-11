<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../ok.php';

describe("let", function () {
    let("usage", "run before each test case");

    let("note", "let statements are run in order");

    let("yanote",
        "let statement is shorthand to do assignment in before_each hook");

    let("closure_will_be_auto_invoked", function () {
        return "invoked";
    });

    let("object_is_cloned", new \stdClass);

    let("value", "string");

    it("can access the variable set in before_each hooks", function () {
        ok($this->usage);
        ok($this->note);
        ok($this->value);
        ok($this->closure_will_be_auto_invoked == "invoked");
        ok(empty($this->object_is_cloned->name));

        $this->object_is_cloned->name = "assigned new value";
        $this->value = null;
    });

    it("before_each hooks are run before current test", function () {
        ok($this->value); // string is passed by value
        ok(empty($this->object_is_cloned->name)); // object is passed by "ref".
    });
});