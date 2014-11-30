<?php
namespace Preview\DSL\BDD;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

describe("Stack", function () {
    before_each(function () {
        $this->stack = new \Stack(array(1,2,3));
    });

    describe("#size", function () {
        it("returns the size of stack", function () {
            ok($this->stack->size() == 3);
        });
    });

    describe("#peek", function () {
        it("returns the last element", function () {
            ok($this->stack->peek() == 3);
        });
    });

    describe("#push", function () {
        it("pushes an element to stack", function () {
            $this->stack->push(4);
            ok($this->stack->peek() == 4);
            ok($this->stack->size() == 4);
        });
    });

    describe("#pop", function () {
        it("pops out the last element", function () {
            ok($this->stack->pop() == 3);
            ok($this->stack->size() == 2);
        });
    });
});
