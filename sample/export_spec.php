<?php
namespace Preview\DSL\Export;

require_once 'ok.php';

$suite = [
    "tests" => [
        "yay" => function () {
            ok(false);
        },
    ],

    "child suite" => [
        "tests" => [
            "wow" => function () {
                ok(true);
            },
        ],
    ],
];

export("sample", $suite);
