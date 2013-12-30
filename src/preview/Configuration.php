<?php
/**
 * Configuration for preview
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview;

class Configuration {
    /**
     * Exception types which will be catched as a test failure error message.
     *
     * @var array $assertion_errors default is array("\\Exception")
     */
    public $assertion_errors = array("\\Exception");

    /**
     * Reporter object.
     *
     * @var object $reporter
     */
    public $reporter = null;

    /**
     * Use color for terminal report
     *
     * @var bool $color_support default is true.
     */
    public $color_support = true;

    /**
     * Specify the test groups to run.
     * Default is all which means run all the tests.
     *
     * @var array $test_groups default is empty array
     */
    public $test_groups = array();

    /**
     * If this property set to true,
     * the context object will be the $this used
     * in the testcase callback and other before/after hooks.
     * Otherwise context object will be passed as an arguments to them.
     *
     * @var bool $use_implicit_context default is true.
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
     * Name of a shared directory which contains shared_example spec file.
     * This dir will be first loaded before any other spec file.
     *
     * @var string $shared_dir_name;
     */
    public $shared_dir_name = 'shared';

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options=array()) {
        $attrs = array_keys(get_object_vars($this));
        foreach ($options as $key => $value) {
            if (in_array($key, $attrs)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Load config from file
     *
     * @param string $file config file path
     * @retrun object Configuration object.
     */
    public static function load_from_file($file) {
        $options = require_once $file;
        if (!is_array($options)) {
            $options = array();
        }

        if (isset($options["reporter"])) {
            $reporter = $options["reporter"];
            if (is_string($reporter)) {
                $options["reporter"] = new $reporter;
            }
        }

        return new self($options);
    }
}