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
     * groups it belongs to
     *
     * @var array $groups
     */
    public $groups = array();

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
            if (Configuration::$use_implicit_context) {
                $this->fn = $ref->getClosure();
            }
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
     * Add test child suite/case
     *
     * @param object $suite
     * @retrun object $this
     */
    public function add($suite_or_case) {
        $suite_or_case->set_parent($this);
        return $this;
    }

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
     * make this test as a member of a test group;
     *
     * @param string group names
     * @retrun object $this
     */
    public function add_to_group($group) {
        if (!in_array($group, $this->groups)) {
            $this->groups[] = $group;
        }
    }

    /**
     * Check if the test is in test groups
     *
     * @param null
     * @retrun bool
     */
    public function in_test_group() {
        if (empty(Configuration::$test_groups)) {
            return true;
        }

        foreach (Configuration::$test_groups as $group) {
            if ($this->in_group($group)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if test is in a certain group.
     *
     * @param string $group
     * @retrun bool
     */
    public function in_group($group) {
        return in_array($group, $this->groups);
    }

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
     * Test Suite is runnable if test is neither finished, skipped nor pending.
     * But for test case only the ones that belongs to any test group can run.
     * So this method will be overidden in TestCase class.
     *
     * @param null
     * @return bool
     */
    public function runnable() {
        return !$this->finished and
            !$this->skipped and
            !$this->pending;
    }

    /**
     * Merge current context with parent context if it has a parent testsuite.
     *
     * @param null
     * @retrun stdClass current context;
     */
    protected function extend_context_with_parent() {
        if ($this->parent) {
            $this->context = (object) array_merge((array) $this->context,
                                                  (array) $this->parent->context);
        }
        return $this->context;
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