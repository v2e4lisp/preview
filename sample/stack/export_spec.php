<?php
namespace Preview\DSL\Export;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

$suite = array(
    "before each" => function () {
        $this->stack = new \Stack(array(1,2,3));
    },

    "#sizereturns the size of stack" => function () {
        ok($this->stack->size() == 3);
    },

    "#peek eturns the last element" => function () {
        ok($this->stack->peek() == 3);
    },

    "#push pushes an element to stack" => function () {
        $this->stack->push(4);
        ok($this->stack->peek() == 4);
        ok($this->stack->size() == 4);
    },

    "#pop pops out the last element" => function () {
        ok($this->stack->pop() == 3);
        ok($this->stack->size() == 2);
    }
);

export("Stack", $suite);
