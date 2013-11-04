<?php

class Configuration {
    public static $assertion_errors = array(Exception);
    public static $reporter = null;

    public static function set_reporter($reporter) {
        self::$reporter = $reporter;
    }

    public static function set_assertion_errors($errors) {
        if (!is_array($errors)) {
            $errors = array($errors);
        }
        self::$assertion_errors = $errors;
    }
}