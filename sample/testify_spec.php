<?php

namespace Preview\DSL\Testify;

require_once 'ok.php';

$suite = new Suite("array functions");
$child = new Suite("String functions");
$suite->add_child_suite($child);

$child->test(function () {
    ok(true);
});

$suite->before_each(function () {
    $this->arr = array(1,2,3,4);
});

$suite->test("array_push", function () {
    array_push($this->arr, 1);
    ok(end($this->arr) == 1);
});

$suite->test("array_pop", "pop", function () {
    array_pop($this->arr);
    ok(end($this->arr) == 3);
});

$suite->load();
