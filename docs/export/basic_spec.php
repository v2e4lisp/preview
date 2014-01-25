<?php
/*
 * Basic usage
 */

namespace Preview\DSL\Export;

require_once __DIR__.'/../ok.php';

$suite1 = array(
    "create a test case" => function () {
        ok(true);
    },

    "create a another test case" => function () {
        ok(true);
    },
);


$suite2 = array(
    "create a test case" => function () {
        ok(true);
    },

    "create a another test case" => function () {
        ok(true);
    },

    "nested test suite" => array(

        "test case passed" => function () {
            ok(true);
        },

        "test case failed" => function () {
            ok(false);
        },
    ),
);

export("test suite", $suite1);
export($suite2); // load suite without description.
