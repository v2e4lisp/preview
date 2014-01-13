<?php
namespace Preview\DSL\BDD;

use Preview\Configuration;

require_once 'helper.php';

describe("Configuration", function () {
    before(function () {
        $this->config = new Configuration;
    });

    context("with default value", function() {
        it("assertion exceptions should be Exception", function () {
            ok($this->config->assertion_exceptions ==
               array("\\Exception"));
        });

        it("conver error to exception should be true", function () {
            ok($this->config->convert_error_to_exception === true);
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

        it("shared_dir_name should be shared", function () {
            ok($this->config->shared_dir_name == "shared");
        });

        it("spec_file_regexp should be _spec.php", function () {
            ok($this->config->spec_file_regexp == '/_spec\.php/');
        });
    });
});
