<?php
namespace Preview\DSL\BDD;

require_once 'ok.php';


describe("error", function () {
    it("should be converted to error_exception", function () {
        $a->value;
    });

    it("should be ok", function () {
        trigger_error("This event WILL fire", E_USER_NOTICE);
        ok(true);
    });
});


