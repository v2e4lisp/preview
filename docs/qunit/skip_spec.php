<?php
namespace Preview\DSL\Qunit;

require_once __DIR__.'/../ok.php';

suite("A skipped test suite")->skip();

test("A skipped test case", function () {
    ok(true);
})->skip();
