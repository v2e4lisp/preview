<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../ok.php';

describe("after", function () {
    after(function () {
        $this->usage = "run after the current test suite";
    });

    after(function () {
        $this->note = "after hooks are run in order";
    });
});
