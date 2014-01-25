<?php
/*
 * How to use after_each hook
 */

namespace Preview\DSL\Export;

require_once __DIR__.'/../ok.php';

$suite = array(
    "after each" => function () {
        $this->usage = "run after each test case.";
    },

    "ok" => function () {
        ok(true);
    },
);

export("[after each]", $suite);
