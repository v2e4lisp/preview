<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../ok.php';


describe("A skipped test suite.", function () {
})->skip();

describe("Not skipped test suite.", function () {

    it("A skipped test case.", function () {
    })->skip();

    it("Not skipped test case.", function () {});
});
