<?php
namespace Preview\DSL\BDD;

use Preview\Configuration;

require_once 'ok.php';

describe("Configuration", function () {
    before(function () {
        $this->config = new Configuration;
    });

    context("default", function() {
        it("assertion errors should be Exception", function () {
            ok($this->config->assertion_errors == array("\\Exception"));
        });

        it("reporter should not be set", function () {
            ok(!isset($this->config->reportor));
        });

        it("color support should be turned on", function () {
            ok($this->config->color_support);
        });

        it("test groups should be empty", function () {
            ok(empty($this->test_groups));
        });

        it("use implicit context", function () {
            ok($this->config->use_implicit_context);
        });
    });
});
