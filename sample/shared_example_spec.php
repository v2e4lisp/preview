<?php

namespace Preview\DSL\BDD;

require_once 'ok.php';

shared_example("stack", function () {
    describe("#pop", function () {
        it ("should work", function () {
            ok(true);
        });
    });
});

describe("world", function () {
    it_behaves_like("stack");
});