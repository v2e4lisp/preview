<?php
/**
 * Test World class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview;

class World {
    /**
     * Array of test suite objects without parent
     *
     * @var array $start_points
     */
    protected $start_points = array();

    /**
     * array of TestSuite objects to track nested test suites.
     *
     * @var array $testsuite_chain
     */
    protected $testsuite_chain = array();

    /**
     * all test groups
     *
     * @var array $groups
     */
    protected $groups = array();

    /**
     * Get current test suite
     *
     * @param null
     * @return object|null
     */
    public function current() {
        if (empty($this->testsuite_chain)) {
            return null;
        }
        return end($this->testsuite_chain);
    }

    /**
     * Push test suite to $testsuite_chain.
     *
     * @param object $testsuite
     * @return null
     */
    public function push($testsuite) {
        if (empty($this->testsuite_chain)) {
            $this->start_points[] = $testsuite;
        }

        $this->testsuite_chain[] = $testsuite;
    }

    /**
     * Pop out a test suite from $testsuite_chain
     *
     * @param null
     * @return object|null
     */
    public function pop() {
        return array_pop($this->testsuite_chain);
    }

    /**
     * Run all the tests from $start_points.
     * the result of start point test suites will return
     *
     * @param null
     * @return array
     */
    public function run() {
        $tests = $this->filter_test_by_group();
        $runner = new Runner($tests);
        return $runner->run();
    }

    /**
     * Get all test groups
     *
     * @param null
     * @retrun array an array of test group names(string)
     */
    public function groups() {
        return $this->groups;
    }

    /**
     * add a test case/suite to group
     *
     * @param object $test TestCase/TestSuite object
     * @retrun string $group group name
     */
    public function add_test_to_group($test, $group) {
        if(isset($this->groups[$group])) {
            $this->groups[$group] = array();
        }
        $this->groups[$group][] = $test;
    }

    /**
     * filter tests by groups which is set in Configuration.
     *
     * @param null
     * @retrun array an array of test object.
     */
    private function filter_test_by_group() {
        if (empty(Preview::$config->test_groups)) {
            return $this->start_points;
        }

        $tests = array();
        foreach(Preview::$config->test_groups as $group) {
            if(isset($this->groups[$group])) {
                $tests = array_merge($tests, $this->groups[$group]);
            }
        }
        return array_unique($tests);
    }
}