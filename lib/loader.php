<?php

namespace Preview;

class Loader {
    public static $postfix = "_spec.php";

    public static function load($path) {
        $path = realpath($path);
        if (is_dir($path)) {
            self::load_dir($path);
        } else {
            self::load_file($path);
        }
    }

    public static function load_file($path) {
        if (!file_exists($path) or !self::endswith($path, self::$postfix)) {
            return false;
        }

        require_once $path;
        return true;
    }

    protected static function load_dir($path) {
        if (!file_exists($path)) {
            return false;
        }

        foreach (scandir($path) as $p) {
            if ($p[0] != ".") {
                self::load("{$path}/{$p}");
            }
        }
    }


    private static function endswith($haystack, $needle) {
        return $needle === "" ||
            substr($haystack, -strlen($needle)) === $needle;
    }
}