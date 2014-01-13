<?php
/*
 * How to use after hook.
 */

namespace Preview\DSL\Export;

require_once __DIR__.'/../ok.php';

$suite = array(
    "after" => function () {
        $this->usage = "run after current test suite.";
    },
);

export("[after]", $suite);
