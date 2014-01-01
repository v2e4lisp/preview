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
     * Test runner object
     *
     * @var object $runner
     */
    protected $runner = null;

    /**
     * Constructor
     *
     * @param null
     */
    public function __construct() {
        $this->runner = new Runner;
    }

    /**
     * Reset the world
     *
     * @param null
     * @retrun null
     */
    public function reset() {
        $this->throw_exception_if_running("current");
        $this->start_points = array();
        $this->testsuite_chain = array();
    }

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

        // setup error handler
        if (Preview::$config->error_exception) {
            set_error_handler(function ($no, $str, $file, $line) {
                throw new \ErrorException($str, $no, 0, $file, $line);
            });
        }

        $this->running = true;
        $this->runner->set_start_points($this->start_points);
        $results = $this->runner->run();
        $this->running = false;

        return $results;
    }

    public function force_exit($code=1) {
        $this->running = false;
        $this->runner->force_stop();
        exit($code);
    }

    /**
     * Get all test groups
     *
     * @param null
     * @retrun array an array of test group names(string)
     */
    public function groups() {
        $this->throw_exception_if_running("run");

        $groups = array();
        foreach($this->start_points as $suite) {
            $groups = array_merge($groups, $suite->groups);
        }
        return array_unique($groups);
    }

    /**
     * Add a shared test.
     *
     * @param string $name shared test name
     * @param function $fn
     * @retrun null
     */
    public function add_shared_example($shared) {
        $this->throw_exception_if_running("add_shared_example");
        $this->shared_examples[$shared->name()] = $shared;
    }

    /**
     * Get shared test by name
     *
     * @param string $name shared test name.
     * @retrun object|false shared test
     */
    public function shared_example($name) {
        $this->throw_exception_if_running("shared_example");
        if (array_key_exists($name, $this->shared_examples)) {
            return $this->shared_examples[$name];
        }
        return null;
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
            throw new \ErrorException("You can't call World#$name ".
                                      "while test world is running. ".
                                      "This error occures when you try to ".
                                      "create a test suite or new test case".
                                      "in test case context.");
        }
    }
}