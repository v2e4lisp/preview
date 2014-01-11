<?php
namespace Preview\DSL\Qunit;

require_once __DIR__.'/../ok.php';

suite("Create a test suite");

test("Run a test", function () {
    ok(true);
});

test("Run a another test", function () {
    ok(true);
});

// test with no description
test(function () {
    ok(true);
});

suite("Create a another test suite, ".
      "So the following test case belongs to this one");

test("A test case for the new suite", function () {
    ok(true);
});