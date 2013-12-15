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
     * @param array|string $param Exception type(s)
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
}