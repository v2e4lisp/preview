<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../ok.php';

describe("A pending test suite");

describe("Not pending if it has an callback function.", function () {
    it("A pending test case");

    it("Not pending it has an callback function", function () {});
});