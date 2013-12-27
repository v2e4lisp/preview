<?php
/**
 * Test Runner class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview;

class Runner {
    /**
     * test objects (suites or cases)
     *
     * @var array $tests
     */
    protected $tests = array();

    /**
     * Constructor
     *
     * @param array $tests array of test suites/cases
     */
    public function __construct($tests) {
        $this->tests = $tests;
        shuffle($this->tests);
    }

    /**
     * run the test suites/cases and recursively run all test cases.
     *
     * @param null
     * @retrun array array of test result objects.
     */
    public function run() {
        $results = array();
        foreach($this->tests as $test) {
            $results[] = $test->result;
        }

        Preview::$config->reporter->before_all($results);
        foreach($this->tests as $test) {
            $test->run();
        }
        Preview::$config->reporter->after_all($results);

        return $results;
    }
}