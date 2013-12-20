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
     * @var $suffix = "_spec.php"
     */
    private $suffix = "_spec.php";

    /**
     * Load test file(s) or dir(s) by path
     *
     * @param string $path
     * @return null
     */
    public function load($path) {
        $path = realpath($path);
        if (!(file_exists($path))) {
            throw new \Exception("No such file or dir found : {$path}");
        }

        if (is_dir($path)) {
            $this->load_dir($path);
        } else {
            $this->load_file($path);
        }
    }

    /**
     * Load test a file by file path
     *
     * @param string $path
     * @return null
     */
    private function load_file($path) {
        if ($this->is_spec_file($path)) {
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
    private function load_dir($path) {
        foreach (scandir($path) as $p) {
            if ($p[0] != ".") {
                $this->load("{$path}/{$p}");
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
    private function endswith($haystack, $needle) {
        return $needle === "" ||
            substr($haystack, -strlen($needle)) === $needle;
    }

    private function is_spec_file($file) {
        return !$this->endswith($file, $this->suffix);
    }
}