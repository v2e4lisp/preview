<?php
/**
 * TestBase class
 * Base class for TestCase and TestSuite
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Core;

use Preview\Timer;
use Preview\Configuration;

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
     * Test groups
     *
     * @var array $groups
     */
    public $groups = array("all");

    /**
     * A test state flag. test in test group(s) specified in configuration.
     *
     * @var array|null $in_test_group
     */
    public $in_test_group = null;

    /**
     * A lambda contains contenst of the test.
     * If not set it means the test is pending.
     *
     * @var function|null $fn
     */
    protected $fn;

    /**
     * context to this test and all related hooks.
     *
     * @var object|null $context
     */
    protected $context = null;


    public function __construct($title, $fn) {
        $this->context = new \stdClass;
        $this->title = $title;
        $this->pending = !isset($fn);
        $this->timer = new Timer;
        $this->fn = $fn;

        if ($fn) {
            $ref = new \ReflectionFunction($fn);
            $this->filename = $ref->getFileName();
            $this->startline = $ref->getStartLine();
            $this->endline = $ref->getEndLine();
        }
    }

    /**
     * run test
     *
     * @param null
     * @retrun null
     */
    public function run() {}

    /**
     * set parent suite
     *
     * @param object $suite parent testsuite.
     * @retrun null
     */
    public function set_parent($suite) {}

    /**
     * set error object for failed test.
     *
     * @param object $error
     * @retrun null
     */
    public function set_error($error) {
        if ($this->error) {
            return;
        }
        $this->error = $error;

        if ($this->parent) {
            $this->parent->set_error($error);
        }
    }

    /**
     * set group(s) for this test case/suite
     *
     * @param string group names
     * @retrun object $this
     */
    public function group() {
        $groups = func_get_args();
        foreach ($groups as $g) {
            $this->groups[] = $g;
        }
        return $this;
    }

    /**
     * check if test is in a certain group.
     *
     * @param string $group
     * @retrun bool
     */
    public function in_group($group) {
        return in_array($group, $this->groups);
    }

    /**
     * check if test is in the test groups
     *
     * @param string $param
     * @retrun null
     */
    public function in_test_group() {}

    /**
     * Running time of this test case/suite;
     *
     * @param null
     * @retrun float seconds
     */
    public function time() {
        return $this->timer->span();
    }

    /**
     * Skip this test.
     *
     * @param null
     * @return object $this
     */
    public function skip() {
        if ($this->fn) {
            $this->skipped = true;
        }
        return $this;
    }

    /**
     * Mark this test finished.
     *
     * @param null
     * @return object $this
     */
    public function finish() {
        $this->finished = true;
        return $this;
    }

    /**
     * Check if the test is runnable.
     * Test is runnable if test is neither finished, skipped nor pending.
     * and in test group.
     *
     * @param null
     * @return bool
     */
    public function runnable() {
        return !$this->finished and
            !$this->skipped and
            !$this->pending and
            $this->in_test_group();
    }

    /**
     * Invoke a closure with context(explicitly or implicitly)
     * if Configuration::$use_implicit_context is set to true,
     * the closure will be bound to the context object.
     * Otherwise context will be passed to closure as an argument.
     *
     * @param function $fn
     * @param object context object (new stdClass)
     * @retrun mixed
     */
    protected function invoke_closure_with_context($fn, $context) {
        if (Configuration::$use_implicit_context) {
            return $fn->bindTo($context, $context)->__invoke();
        } else {
            return $fn->__invoke($context);
        }
    }
}