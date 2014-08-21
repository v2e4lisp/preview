<?php
/**
 * reporter base class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Reporter;

use Preview\Preview;

class Base {
    /**
     * A hook to be called before each test case
     *
     * @param object A test case result object.
     * @retrun null
     */
    public function before_case($case) {}

    /**
     * A hook to be called after each test case
     *
     * @param object A test case result object.
     * @retrun null
     */
    public function after_case($case) {}

    /**
     * A hook to be called before each test suite
     *
     * @param object A test suite result object.
     * @retrun null
     */
    public function before_suite($suit) {}

    /**
     * A hook to be called after each test suite
     *
     * @param object A test suite result object.
     * @retrun null
     */
    public function after_suite($suite) {}

    /**
     * A hook to be called before all test suites/cases.
     *
     * @param array array of test suite result objects.
     * @retrun null
     */
    public function before_all($results) {}

    /**
     * A hook to be called after all test suite/cases.
     *
     * @param array array of test suites/cases result objects.
     * @retrun null
     */
    public function after_all($results) {}

    /**
     * Get total time
     *
     * @param array $results array of test results
     * @retrun float time
     */
    public function timespan($results) {
        $time = 0;
        foreach($results as $result) {
            $time += $result->time();
        }
        return $time;
    }


    /**
     * Filter out trace message from Preview file.
     *
     * @param string orignal trace message ($e->getTraceAsString())
     * @retrun string
     */
    protected function trace_message($trace) {
        if (Preview::$config->full_backtrace) {
            return trim($trace).Util::br();
        }

        $message = "";
        $msg_list = array_filter(explode(Util::br(), $trace));
        array_pop($msg_list);
        foreach($msg_list as $msg) {
            if (!$this->from_preview_file($msg)) {
                $message .= $msg.Util::br();
            }
        }
        return $message;
    }

    /**
     * check if string contain Preview file path.
     *
     * @param string $path
     * @retrun bool
     */
    protected function from_preview_file($path) {
        $preview_dir = dirname(dirname(__DIR__));
        $preview_bin = dirname($preview_dir).DIRECTORY_SEPARATOR."preview";
        return strpos($path, $preview_dir) !== false or
            strpos($path, $preview_bin) !== false;
    }
}