<?php
/**
 * TestBase class
 * base class for TestCase and TestSuite
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Core;

use Preview\Timer;

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

    public $in_test_group = null;

    /**
     * A lambda contains contenst of the test.
     * If not set it means the test is pending.
     *
     * @var function|null $fn
     */
    protected $fn;

    public function __construct($title, $fn) {
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

    public function in_group($group) {
        return in_array($group, $this->groups);
    }

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
     * Check if test is runnable.
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
}