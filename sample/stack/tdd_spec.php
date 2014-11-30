<?php
namespace Preview\DSL\TDD;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

suite("Stack", function () {
    setup(function () {
        $this->stack = new \Stack(array(1,2,3));
    });

    suite("#size", function () {
        test("returns the size of stack", function () {
            ok($this->stack->size() == 3);
        });
    });

    suite("#peek", function () {
        test("returns the last element", function () {
            ok($this->stack->peek() == 3);
        });
    });

    suite("#push", function () {
        test("pushes an element to stack", function () {
            $this->stack->push(4);
            ok($this->stack->peek() == 4);
            ok($this->stack->size() == 4);
        });
    });

    suite("#pop", function () {
        test("pops out the last element", function () {
            ok($this->stack->pop() == 3);
            ok($this->stack->size() == 2);
        });
    });
});
