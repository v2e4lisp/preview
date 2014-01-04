<?php
namespace Preview\DSL\BDD;

require_once 'ok.php';


describe("error", function () {
    it("should be fail", function () {
        ok(false);
    });
    it("should be fail", function () {
        throw new \Exception("fail.............");
    });
});


