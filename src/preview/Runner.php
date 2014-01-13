<?php
namespace Preview;

/**
 * Test Runner class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */
class Runner {
    /**
     * test objects (suites or cases)
     *
     * @var array $groups
     */
    public $start_points = array();

    /**
     * test results
     *
     * @var array $results array of test results object.
     */
    public $results = array();

    /**
     * Constructor
     *
     * @param array $tests array of test suites/cases
     */
    public function __construct($start_points = array()) {
        $this->start_points = $start_points;
    }

    /**
     * Set start point tests
     *
     * @param array an array of test suite object.
     * @retrun null
     */
    public function set_start_points($start_points) {
        $this->start_points = $start_points;
    }

    /**
     * run the test suites/cases and recursively run all test cases.
     *
     * @param null
     * @retrun array array of test result objects.
     */
    public function run() {
        $tests = $this->filter_tests();

        if (!Preview::$config->order) {
            shuffle($tests);
        }

        foreach($tests as $test) {
            $this->results[] = $test->result;
        }

        Preview::$config->reporter->before_all($this->results);
        foreach($tests as $test) {
            $test->run();
        }
        Preview::$config->reporter->after_all($this->results);

        return $this->results;
    }

    /**
     * Force runner stop running.
     *
     * @param null
     * @retrun array an array of test results object.
     */
    public function force_stop() {
        Preview::$config->reporter->after_all($this->results);
        return $this->results;
    }

    /**
     * filter out unrelated tests according to $config->test_groups.
     *
     * @param null
     * @retrun array an array of start_point test suites
     */
    public function filter_tests() {
        if (empty(Preview::$config->test_groups)) {
            return $this->start_points;
        }

        $roots = array();
        foreach($this->start_points as $suite) {
            if (!$suite->in_test_group()) {
                continue;
            }

            $roots[] = $suite;
            $this->filter_suite($suite);
        }
        return $roots;
    }

    /**
     * Recursively filter out unrelated test for one test suite.
     *
     * @param string $param
     * @retrun null
     */
    private function filter_suite($suite) {
        foreach($suite->cases as $child) {
            if (!$child->in_test_group()) {
                $suite->remove($child);
            }
        }

        foreach($suite->suites as $child) {
            if (!$child->in_test_group()) {
                $suite->remove($child);
            }

            $this->filter_suite($child);
        }
    }
}