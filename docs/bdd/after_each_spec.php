<?php
/*
 * How to use after_each.
 */

namespace Preview\DSL\BDD;

require_once __DIR__.'/../ok.php';

describe("after_each", function () {
    after_each(function () {
        $this->usage = "run after_each each test case";
    });

    after_each(function () {
        $this->note = "after_each hooks are run in order";
    });
});
