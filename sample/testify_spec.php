<?php

namespace Preview\DSL\Testify;

require_once __DIR__ . '/../test/ok.php';

$suite = new Suite("array functions");

$suite->before_each(function () {
    $this->arr = array(1,2,3,4);
});

$suite->test("array_push", function () {
    array_push($this->arr, 1);
    ok(end($this->arr) == 1);
});

$suite->test("array_pop", function () {
    array_pop($this->arr);
    ok(end($this->arr) == 3);
})->group("pop");
