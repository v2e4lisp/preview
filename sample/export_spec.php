<?php
namespace Preview\DSL\Export;

require_once 'ok.php';

$suite = [
    "yay" => function () {
        ok(false);
    },
];

export("sample", $suite);
