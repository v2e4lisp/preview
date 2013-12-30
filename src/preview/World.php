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
     * Test is running or not.
     *
     * @var bool $running
     */
    protected $running = false;

    /**
     * shared tests
     *
     * @var array $shared_examples array of TestShared objects.
     */
    protected $shared_examples = array();

    /**
     * Get current test suite
     *
     * @param null
     * @return object|null
     */
    public function current() {
        $this->throw_exception_if_running("current");
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
        $this->throw_exception_if_running("push");
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
        $this->throw_exception_if_running("pop");
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
        $this->throw_exception_if_running("run");
        $this->running = true;
        $tests = $this->filter_test_by_group();
        $runner = new Runner($tests);
        $results = $runner->run();
        $this->running = false;
        return $results;
    }

    /**
     * Get all test groups
     *
     * @param null
     * @retrun array an array of test group names(string)
     */
    public function groups() {
        $this->throw_exception_if_running("run");
        return $this->groups;
    }

    /**
     * Add a test case/suite to group
     *
     * @param object $test TestCase/TestSuite object
     * @retrun string $group group name
     */
    public function add_test_to_group($test, $group) {
        $this->throw_exception_if_running("add_test_to_group");
        if(isset($this->groups[$group])) {
            $this->groups[$group] = array();
        }
        $this->groups[$group][] = $test;
    }

    /**
     * Add a shared test.
     *
     * @param string $name shared test name
     * @param function $fn
     * @retrun null
     */
    public function add_shared_example($shared) {
        $this->throw_exception_if_running("add_test_to_group");
        $this->shared_examples[$shared->name()] = $shared;
    }

    /**
     * Get shared test by name
     *
     * @param string $name shared test name.
     * @retrun object|false shared test
     */
    public function shared_example($name) {
        $this->throw_exception_if_running("add_test_to_group");
        if (array_key_exists($name, $this->shared_examples)) {
            return $this->shared_examples[$name];
        }
        return null;
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

    /**
     * This function will freeze the world if tests are running.
     * Which means you cannot call any method on the current World.
     *
     * @param string $param
     * @retrun null
     */
    private function throw_exception_if_running($name) {
        if ($this->running) {
            throw new \Exception("You can't call World#$name ".
                                 "while test world is running. ".
                                 "This error occures when you try to ".
                                 "create a test suite or new test case".
                                 "in test case context.");
        }
    }
}