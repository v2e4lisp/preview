<?php

class Configuration {
    public static $assertion_errors = array("Exception");
    public static $reporter = null;

    public static function setReporter($reporter) {
        self::$reporter = $reporter;
    }

    public static function setAssertionErrors($errors=null) {
        if (empty($errors)) {
            self::$assertion_errors = array();
            return;
        }

        if (!is_array($errors)) {
            $errors = array($errors);
        }
        self::$assertion_errors = $errors;
    }
}