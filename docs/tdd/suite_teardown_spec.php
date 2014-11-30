<?php
/*
 * How to use suite_teardown.
 */

namespace Preview\DSL\TDD;

require_once __DIR__.'/../ok.php';

suite("suite_teardown", function () {
    suite_teardown(function () {
        $this->usage = "run suite_teardown the current test suite";
    });

    suite_teardown(function () {
        $this->note = "suite_teardown hooks are run in order";
    });
});
