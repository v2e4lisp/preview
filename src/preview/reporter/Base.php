<?php
/**
 * reporter base class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Reporter;

class Base {
    /**
     * A hook to be called before each test case
     *
     * @param object A test case result object.
     * @retrun null
     */
    public function before_case($case) {
    }

    /**
     * A hook to be called after each test case
     *
     * @param object A test case result object.
     * @retrun null
     */
    public function after_case($case) {
    }

    /**
     * A hook to be called before each test suite
     *
     * @param object A test suite result object.
     * @retrun null
     */
    public function before_suite($suit) {
    }

    /**
     * A hook to be called after each test suite
     *
     * @param object A test suite result object.
     * @retrun null
     */
    public function after_suite($suite) {
    }

    /**
     * A hook to be called before all test suite.
     *
     * @param array array of test suite result objects.
     *              test suites are the start points of the test world
     * @retrun null
     */
    public function before_all($results) {
    }

    /**
     * A hook to be called after all test suite.
     *
     * @param array array of test suite result objects.
     *              test suites are the start points of the test world
     * @retrun null
     */
    public function after_all($results) {
    }
}