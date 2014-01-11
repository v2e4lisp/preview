<?php
namespace Preview\DSL\Qunit;

require_once __DIR__.'/../ok.php';

suite("A grouped test suite")->group("sample group");

test("A skipped test case", function () {
    ok(true);
})->group("test group", "another group");
