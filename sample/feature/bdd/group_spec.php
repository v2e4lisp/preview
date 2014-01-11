<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../../ok.php';


describe("add this test suite to groups", function () {

    it("add this test case to testcase group", function () {
    })->group("testcase");

})->group("sample", "group them all");