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
     * @var array $assertion_errors Default is array("\\Exception")
     */
    protected static $assertion_errors = array("\\Exception");

    /**
     * Reporter object.
     *
     * @var object $reporter
     */
    protected static $reporter = null;

    /**
     * Specify the test groups to run.
     * Default is all which means run all the tests.
     *
     * @var array $test_groups
     */
    public static $test_groups = array();

    /**
     * If this property set to true, the context object will be the $this used
     * in the testcase callback and other before/after hooks.
     * Otherwise context object will be passed as an arguments to them.
     *
     * @var bool $use_implicit_context default is true.
     */
    public static $use_implicit_context = true;

    /**
     * Set reporter
     *
     * @param object $reporter
     * @return null
     */
    public static function set_reporter($reporter) {
        self::$reporter = $reporter;
    }

    /**
     * Set error case exceptions
     *
     * @param array|string $errors Exception type(s)
     * @return null
     */
    public static function set_assertion_error($errors=array()) {
        if (!is_array($errors)) {
            $errors = array($errors);
        }
        self::$assertion_errors = $errors;
    }

    /**
     * Get the reporter.
     *
     * @param null
     * @return object
     */
    public static function reporter() {
        return static::$reporter;
    }

    /**
     * Get error case exception type(s)
     *
     * @param string $param
     * @return null
     */
    public static function assertion_error() {
        return static::$assertion_errors;
    }

    public static function php_version_is_53() {
        return version_compare(phpversion(), '5.4', '<');
    }
}