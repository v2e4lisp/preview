<?php
/**
 * Loader class to load test files
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview;

class Loader {
    /**
     * Suffix of test filename
     *
     * @var $postfix = "_spec.php"
     */
    private static $postfix = "_spec.php";

    /**
     * Load test file(s) or dir(s) by path
     *
     * @param string $path
     * @return null
     */
    public static function load($path) {
        $path = realpath($path);
        if (!(file_exists($path))) {
            throw new \Exception("No such file or dir found : {$path}");
        }

        if (is_dir($path)) {
            self::load_dir($path);
        } else {
            self::load_file($path);
        }
    }

    /**
     * Load test a file by file path
     *
     * @param string $path
     * @return null
     */
    private static function load_file($path) {
        if (!self::endswith($path, self::$postfix)) {
            return false;
        }

        require_once $path;
    }

    /**
     * Recursively load all test files in a dir.
     *
     * @param string $path
     * @return null
     */
    private static function load_dir($path) {
        foreach (scandir($path) as $p) {
            if ($p[0] != ".") {
                self::load("{$path}/{$p}");
            }
        }
    }

    /**
     * Check if a string is end with another string
     *
     * @param string $haystack string to check
     * @param string $needle substring
     * @return bool
     */
    private static function endswith($haystack, $needle) {
        return $needle === "" ||
            substr($haystack, -strlen($needle)) === $needle;
    }
}