<?php
namespace Preview\DSL\BDD;

use Preview\Configuration;
use Preview\Preview;
use Preview\Core\TestSuite;
use Preview\Core\TestCase;
use Preview\Reporter\Base as BaseReporter;

require_once 'helper.php';

describe("Configuration", function () {
    before_each(function () {
        $this->config = new Configuration;
    });

    context("with default value", function() {
        it("assertion exceptions should be Exception", function () {
            ok($this->config->assertion_exceptions ==
               array("\\Exception"));
        });

        it("convert error to exception should be true", function () {
            ok($this->config->convert_error_to_exception === true);
        });

        it("reporter should be spec", function () {
            ok("Preview\\Reporter\\Spec" ==
               get_class($this->config->reporter));
        });

        it("color should be set according current stdout", function () {
            ok($this->config->color_support == Preview::is_tty());
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

        it("fail_fast should be false", function () {
            ok($this->config->fail_fast === false);
        });

        it("order should be false", function () {
            ok($this->config->order === false);
        });

        it("full_backtrace should be false", function () {
            ok($this->config->full_backtrace === false);
        });

        it("before_each_hook should be null", function () {
            ok(is_null($this->config->before_each_hook));
        });

        it("before_hook should be null", function () {
            ok(is_null($this->config->before_hook));
        });

        it("after_each_hook should be null", function () {
            ok(is_null($this->config->after_each_hook));
        });

        it("after_hook should be null", function () {
            ok(is_null($this->config->after_hook));
        });
    });

    describe("before_hook", function () {
        it("should run before every test suite", function () {
            $run = false;
            $this->config->reporter = new BaseReporter;
            $this->config->before_hook = function () use (&$run) {
                $run = true;
            };
            $old_config = Preview::$config;
            Preview::$config = $this->config;

            $exception_in_sample_code = null;
            try {
                $suite = new TestSuite("sample suite", function () {});
                $suite->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
            }

            Preview::$config = $old_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }

            ok($run);

        });
    });

    describe("after_hook", function () {
        it("should run after every test suite", function () {
            $run = false;
            $this->config->reporter = new BaseReporter;
            $this->config->after_hook = function () use (&$run) {
                $run = true;
            };
            $old_config = Preview::$config;
            Preview::$config = $this->config;
            $suite = new TestSuite("sample suite", function () {});
            $suite->run();


            $exception_in_sample_code = null;
            try {
                $suite = new TestSuite("sample suite", function () {});
                $suite->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
            }

            Preview::$config = $old_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }

            ok($run);
        });
    });

    describe("before_each_hook", function () {
        it("should run before every test case", function () {
            $run = false;
            $this->config->reporter = new BaseReporter;
            $this->config->before_each_hook = function () use (&$run) {
                $run = true;
            };
            $old_config = Preview::$config;
            Preview::$config = $this->config;

            $exception_in_sample_code = null;
            try {
                $suite = new TestSuite("sample suite", function () {});
                $suite->add(new TestCase("sample case", function () {}));
                $suite->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
            }

            Preview::$config = $old_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }

            ok($run);

        });
    });

    describe("after_each_hook", function () {
        it("should run after every test case", function () {
            $run = false;
            $this->config->reporter = new BaseReporter;
            $this->config->after_each_hook = function () use (&$run) {
                $run = true;
            };
            $old_config = Preview::$config;
            Preview::$config = $this->config;

            $exception_in_sample_code = null;
            try {
                $suite = new TestSuite("sample suite", function () {});
                $suite->add(new TestCase("sample case", function () {}));
                $suite->run();
            } catch (\Exception $e) {
                $exception_in_sample_code = $e;
            }

            Preview::$config = $old_config;

            if ($exception_in_sample_code) {
                throw $exception_in_sample_code;
            }

            ok($run);
        });
    });
});
