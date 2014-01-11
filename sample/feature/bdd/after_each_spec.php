<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../../ok.php';

describe("An sample test suite", function () {
    after(function () {
        $this->usage = "run after_each each test case";
    });

    after(function () {
        $this->note = "after_each hooks are run in order";
    });
});