<?php

namespace Preview\DSL\Qunit;

require_once __DIR__ . '/../test/ok.php';

suite("array_pop");

// run before each test case;
setup(function() {
    $this->arr = array(1,2);
});

// use the variable set in the setup function
test("return last element", function () {
    ok(array_pop($this->arr) == 2);
})->skip();

// skip this case
test("return null for empty string", function () {
    $empty_array = array();
    ok(is_null(array_pop($empty_array)));
});


// test case without description;
test(function() {
    array_pop($this->arr);
    ok(count($this->arr) == 1);
});

// pending test case
test("array_pop a string?");

// another suite;
suite("array_push");

// following test cases now belong to the "array_push" suite
test("add one element to array", function () {
    $target = array(1,2,3,4);
    array_push($target, 5);
    ok(count($target) == 5);
});