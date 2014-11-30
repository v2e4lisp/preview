<?php

namespace Preview\Core;

use Preview\Timer;
use Preview\Preview;

/**
 * TestBase class
 * Base class for TestCase and TestSuite
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
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
     * null if passed otherwise holds an exception object.
     * for test suite it will hold the first failure object.
     *
     * @var object|null $failure
     */
    public $failure = null;

    /**
     * if error occured when running this test,
     * $error will hold a exception.
     *
     * @var object|null $error
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

    /**
     * constructor
     *
     * @param string $title
     * @param function $fn
     */
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
            if (!Preview::is_php53()) {
                $this->fn = $ref->getClosure();
            }
        }
    }

    /**
     * reset test state
     *
     * @param null
     * @return null
     */
    public function reset_state() {
        $this->failure = null;
        $this->error = null;
        $this->skipped = false;
        $this->pending = false;
        $this->finished = false;
    }

    /**
     * run test
     *
     * @param null
     * @return null
     */
    public function run() {}

    /**
     * set parent suite
     *
     * @param object $suite parent testsuite.
     * @return null
     */
    public function set_parent($suite) {}

    /**
     * set failure object for failed test.
     * if any parent , also setup its parent failure
     *
     * @param object $error
     * @return null
     */
    public function set_failure($failure) {
        if ($this->failure) {
            return;
        }
        $this->failure = $failure;

        if ($this->parent) {
            $this->parent->set_failure($failure);
        }
    }

    /**
     * set error object for error test.
     * if any parent , also setup its parent error
     *
     * @param object $error
     * @return null
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
     * @param string group name
     * @return object $this
     */
    public function add_to_group($group) {
        if (!in_array($group, $this->groups)) {
            $this->groups[] = $group;
        }
    }

    /**
     * Check if test is in a certain group.
     *
     * @param string $group
     * @return bool
     */
    public function in_group($group) {
        return in_array($group, $this->groups);
    }

    /**
     * Running time of this test case/suite;
     *
     * @param null
     * @return float seconds
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
        if (!$this->pending) {
            $this->skipped = true;
        }
        return $this;
    }

    /**
     * Check if the test is runnable.
     * Test Suite is runnable if test is neither finished,
     * skipped nor pending.
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
     * Mark this test finished.
     *
     * @param null
     * @return object $this
     */
    protected function finish() {
        $this->finished = true;
        return $this;
    }

    /**
     * Merge current context with parent context
     * if it has a parent testsuite.
     *
     * @param null
     * @return stdClass current context;
     */
    protected function extend_context_with_parent() {
        if ($this->parent) {
            $this->context =
                (object) array_merge((array) $this->parent->context,
                                     (array) $this->context);
        }
        return $this->context;
    }

    /**
     * Invoke a closure with context(explicitly or implicitly)
     * if Preview::$config->use_implicit_context is set to true,
     * the closure will be bound to the context object.
     * Otherwise context will be passed to closure as an argument.
     *
     * @param function $fn
     * @param object context object (new stdClass)
     * @return mixed
     */
    protected function invoke_closure_with_context($fn, $context) {
        if (Preview::$config->use_implicit_context) {
            return $fn->bindTo($context, $context)->__invoke();
        } else {
            return $fn->__invoke($context);
        }
    }
}
