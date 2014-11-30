<?php

namespace Preview\DSL\BDD;
require_once 'ok.php';

describe("parent", function () {
    it("should be ok", function () {
        ok(true);
    });

    describe("here we go", function () {
        before(function () {
            $this->x;
        });

        before_each(function () {
        });

        it("failed", function (){
            $a->xuser;
        });

        it("ok", function (){
        });
    });
});
