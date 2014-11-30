<?php

namespace Preview\DSL\BDD;

require_once 'ok.php';

shared_example("stack", function ($group) {
    describe("#pop", function () {
        it ("should return the last item", function () {
            $end = end($this->subject);
            ok(array_pop($this->subject) == $end);
        });

        it("remove the last item", function () {
        });
    })->group($group);
});

describe("world", function () {
    before_each (function () {
        $this->subject = array(1,2,3);
    });
    it_behaves_like("stack", "hello world");
});
