<?php

namespace Preview\DSL\TDD;

require_once __DIR__ . '/../test/ok.php';

suite("array_pop", function () {
    suite_setup(function () {
        // connect to database.
    });

    // run before each test case;
    setup(function() {
        $this->arr = array(1,2);
    });

    // use the variable set in the setup function
    test("return last element", function () {
        ok(array_pop($this->arr) == 2);
    });

    // skip this case
    test("return null for empty string", function () {
        ok(empty(array_pop(array())));
    })->skip();

    // pending test case
    test("array_pop a string?");
});