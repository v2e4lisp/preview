<?php
namespace Preview\DSL\Qunit;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

suite("Stack");

setup(function () {
    $this->stack = new \Stack(array(1,2,3));
});

test("#size returns the size of stack", function () {
    ok($this->stack->size() == 3);
});

test("#peek eturns the last element", function () {
    ok($this->stack->peek() == 3);
});

test("#push pushes an element to stack", function () {
    $this->stack->push(4);
    ok($this->stack->peek() == 4);
    ok($this->stack->size() == 4);
});

test("#pop pops out the last element", function () {
    ok($this->stack->pop() == 3);
    ok($this->stack->size() == 2);
});