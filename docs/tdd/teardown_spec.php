<?php
/*
 * How to use teardown
 */

namespace Preview\DSL\TDD;

require_once __DIR__.'/../ok.php';

suite("teardown", function () {
    teardown(function () {
        $this->usage = "run teardown each test case";
    });

    teardown(function () {
        $this->note = "teardown hooks are run in order";
    });
});