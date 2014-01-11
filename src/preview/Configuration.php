<?php
/**
 * Configuration for preview
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview;
use Preview\Reporter\Spec as SpecReporter;

class Configuration {
    /**
     * Exception types which will be catched
     * as a test failure object.
     *
     * @var array $assertion_errors default is array("\\Exception")
     */
    public $assertion_errors = array("\\Exception");

    /**
     * Convert php error to an ErrorException
     * so that can be handled by test.
     *
     * @var bool $convert_error_to_exception default true.
     */
    public $convert_error_to_exception = true;

    /**
     * Exit program when first failure happened
     *
     * @var bool $fail_fast default is false
     */
    public $fail_fast = false;

    /**
     * Reporter object.
     *
     * @var object $reporter
     */
    public $reporter = null;

    /**
     * Use color for terminal report
     *
     * @var bool $color_support if tty default is true otherwise false.
     */
    public $color_support = null;

    /**
     * Test run in order
     *
     * @var bool $order default is false, shuffle the tests before run;
     */
    public $order = false;

    /**
     * Specify the test groups to run.
     * Default is all which means run all the tests.
     *
     * @var array $test_groups default is empty array
     */
    public $test_groups = array();

    /**
     * If this property set to true,
     * context object will be the $this used
     * in the testcase callback and other before/after hooks.
     * Otherwise context object will be passed as an arguments to them.
     *
     * @var bool $use_implicit_context
     */
    public $use_implicit_context = true;

    /**
     * File loader object will use this regexp
     * to check if a file is a spec file.
     * Non-spec file will be ignored.
     *
     * @var regexp $filename_regexp
     */
    public $spec_file_regexp = '/_spec\.php/';

    /**
     * Name of a shared directory
     * which contains shared_example spec files.
     * This dir will be first loaded before any other spec file.
     *
     * @var string $shared_dir_name;
     */
    public $shared_dir_name = 'shared';

    /**
     * Global before hook.
     * Run before test suite.
     *
     * @var function|null $before_hook default is null
     */
    public $before_hook = null;

    /**
     * Global after hook.
     * Run after test suite.
     *
     * @var function|null $after_hook default is null
     */
    public $after_hook = null;

    /**
     * Global before each hook.
     * Run before each test case.
     *
     * @var function|null $before_each_hook default is null
     */
    public $before_each_hook = null;

    /**
     * Global after each hook.
     * Run after each test case.
     *
     * @var function|null $after_each_hook default is null
     */
    public $after_each_hook = null;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct ($options=array()) {
        $this->update($options);
    }

    /**
     * Load config from file.
     *
     * @param string $file config file path
     * @retrun object Configuration object.
     */
    public function load_from_file ($file) {
        $options = require_once($file);
        if (!is_array($options)) {
            $options = array();
        }

        $this->update($options);
    }

    /**
     * update the config
     *
     * @param array $options
     * @return null
     */
    public function update ($options) {
        $attrs = array_keys(get_object_vars($this));
        foreach ($options as $key => $value) {
            if (in_array($key, $attrs)) {
                $this->$key = $value;
            }
        }

        $this->set_default_if_not_initialized();
    }

    /**
     * set default config
     *
     * @param null
     * @retrun null
     */
    public function set_default_if_not_initialized () {
        if (is_null($this->color_support)) {
            $this->color_support = Preview::is_tty();
        }

        if (is_null($this->reporter)) {
            $this->reporter = new SpecReporter;
        }

        if (is_string($this->reporter)) {
            $this->reporter = new $this->reporter;
        }

        /*
         * php version specific config
         */
        if (Preview::php_version_is_53()) {
            // Since for PHP version < 5.4 $this is not available for closure,
            // We can't use this feature, instead we explicitly pass
            // the context object as an argument to callback.
            $this->use_implicit_context = false;

        } else {
            // PHP 5.4 and above will automatically
            // bind current $this (if any) to closure.
            // For example, if we call load_from_file method
            // the hooks defined in config file will be bound to
            // the current configuration object($this).
            // So we Unbound hook closure.
            $hooks = array(
                "before_hook",
                "after_hook",
                "before_each_hook",
                "after_each_hook",
            );
            foreach($hooks as $hook) {
                if(!is_null($this->$hook)) {
                    $ref = new \ReflectionFunction($this->$hook);
                    $this->$hook = $ref->getClosure();
                }
            }
        }
    }
}