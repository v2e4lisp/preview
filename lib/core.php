<?php
/**
 * Core classes
 *
 * @package Preview
 * @author Wenjun Yan
 */

namespace Preview;

require_once 'result.php';
require_once 'configuration.php';

/**
 * TestBase class
 * base class for TestCase and TestSuite
 */
class TestBase {
    /**
     * Parent test suite object
     *
     * @var object|null $parent
     */
    public $parent = null;

    /**
     * Whether this test case/suite passed or failed
     * false if passed otherwise holds an exception object.
     * for test suite it will hold the first error object.
     *
     * @var object|false $error
     */
    public $error = null;

    /**
     * A test state flag. Skipped test won't run.
     *
     * @var bool $skipped
     */
    public $skipped = false;

    /**
     * A test state flag. Already finished.
     *
     * @var bool $finished
     */
    public $finished = false;

    /**
     * A test state flag. test not yet implemented.
     *
     * @var bool $pending
     */
    public $pending = false;

    /**
     * Test title
     *
     * @var string $title
     */
    public $title;

    /**
     * Test result object.
     *
     * @var object $result
     */
    public $result;

    /**
     * Filename of the test
     *
     * @var string|null $filename
     */
    public $filename = null;

    /**
     * Start line of the test
     *
     * @var string|null $startline
     */
    public $startline = null;

    /**
     * End line number of the test
     *
     * @var string|null $endline
     */
    public $endline = null;

    /**
     * A lambda contains contenst of the test.
     * If not set it means the test is pending.
     *
     * @var function|null $fn
     */
    protected $fn;

    public function __construct($title, $fn) {
        $this->title = $title;
        $this->fn = $fn;
        $this->pending = !isset($fn);

        if ($this->fn) {
            $ref = new \ReflectionFunction($this->fn);
            $this->filename = $ref->getFileName();
            $this->startline = $ref->getStartLine();
            $this->endline = $ref->getEndLine();
        }
    }

    public function run() {}

    public function set_parent ($suite) {}

    public function set_error($error) {
        if ($this->error) {
            return false;
        }
        $this->error = $error;

        if ($this->parent) {
            $this->parent->set_error($error);
        }
    }

    /**
     * Skip this test.
     *
     * @param null
     * @return null
     */
    public function skip() {
        $this->skipped = true;
    }

    /**
     * Mark this test finished.
     *
     * @param null
     * @return null
     */
    public function finish() {
        $this->finished = true;
    }

    /**
     * Check if test is runnable.
     * Test is runnable if test is neither finished, skipped nor pending.
     *
     * @param string $param
     * @return null
     */
    public function runable() {
        return !$this->finished and
            !$this->skipped and
            !$this->pending;
    }
}

/**
 * Test Case class
 */
class TestCase extends TestBase {
    public function __construct($title, $fn) {
        parent::__construct($title, $fn);
        $this->result = new TestCaseResult($this);
    }

    /**
     * Set parent test suite object
     *
     * @param object $suite: TestSuite object
     * @return null
     */
    public function set_parent($suite) {
        $this->parent = $suite;
        $suite->cases[] = $this;
    }

    /**
     * Run the test.
     * 1. Call Reporter's before_case method
     * 2. Run all the registered before hooks
     * 3. Invoke $fn which contains test statements.
     * 4. All the after hooks
     * 5. Call Reporter's after_case method
     *
     * @param null
     * @return null
     */
    public function run() {
        if (!$this->runable()) {
            return;
        }

        Configuration::reporter()->before_case($this->result);
        $this->parent->run_before_each();

        try {
            $this->fn->__invoke();
        } catch (\Exception $e) {
            foreach(Configuration::assertion_error() as $klass) {
                if ($e instanceof $klass) {
                    $this->set_error($e);
                    break;
                }
            }

            if (!$this->error) {
                throw $e;
            }
        }

        $this->parent->run_after_each();
        $this->finish();
        Configuration::reporter()->after_case($this->result);
    }
}

/**
 * Test suite class
 * It contains children test cases and test suites.
 * Yes, test suites can be nested.
 */
class TestSuite extends TestBase {
    /**
     * before hooks for itself.
     *
     * @var array $before_hooks
     */
    public $before_hooks = array();

    /**
     * before hooks for children test cases
     *
     * @var array $before_each_hooks
     */
    public $before_each_hooks = array();

    /**
     * after hooks for itself
     *
     * @var array $after_hooks
     */
    public $after_hooks = array();

    /**
     * after hooks for children test cases
     *
     * @var array $after_each_hooks
     */
    public $after_each_hooks = array();

    /**
     * children test suite objects
     *
     * @var array $suites
     */
    public $suites = array();

    /**
     * children test case objects
     *
     * @var array $cases
     */
    public $cases = array();

    public function __construct($title, $fn) {
        parent::__construct($title, $fn);
        $this->result = new TestSuiteResult($this);
    }

    /**
     * set parent test suite.
     *
     * @param object|null $suite: parent test suite
     * @return null
     */
    public function set_parent($suite) {
        // if empty($suite), that's a start point of the TEST-WORLD.
        if (!empty($suite)) {
            $suite->suites[] = $this;
            $this->parent = $suite;
        }
    }

    /**
     * Invoke $fn and randomize children cases and suites.
     *
     * @param null
     * @return null
     */
    public function setup() {
        if ($this->pending) {
            return;
        }

        $this->fn->__invoke();
        shuffle($this->cases);
        shuffle($this->suites);
    }

    /**
     * Run this suites.
     * 1. Call Reporter's before_suite method.
     * 2. Run before hooks.
     * 3. Run children test cases and suites.
     * 4. Run after hooks.
     * 5. Call Reporters's after_suite method.
     *
     * @param null
     * @return null
     */
    public function run() {
        if (!$this->runable()) {
            return;
        }

        Configuration::reporter()->before_suite($this->result);
        $this->run_before();
        foreach ($this->cases as $case) {
            $case->run();
        }
        foreach ($this->suites as $suite) {
            $suite->run();
        }
        $this->finish();
        $this->run_after();
        Configuration::reporter()->after_suite($this->result);
    }

    /**
     * Run before hooks.
     *
     * @param null
     * @return null
     */
    public function run_before() {
        foreach ($this->before_hooks as $before) {
            $before->__invoke();
        }
    }

    /**
     * Run children cases' before hooks
     * This method should be called only by its children test cases.
     *
     * @param null
     * @return null
     */
    public function run_before_each() {
        if ($this->parent) {
            $this->parent->run_before_each();
        }

        foreach ($this->before_each_hooks as $before) {
            $before->__invoke();
        }
    }

    /**
     * run after hooks
     *
     * @param null
     * @return null
     */
    public function run_after() {
        foreach ($this->after_hooks as $after) {
            $after->__invoke();
        }
    }

    /**
     * Run children test cases' after hooks
     * This method should be called only by its children test cases.
     *
     * @param null
     * @return null
     */
    public function run_after_each() {
        if ($this->parent) {
            $this->parent->run_after_each();
        }

        foreach ($this->after_each_hooks as $after) {
            $after->__invoke();
        }
    }
}
