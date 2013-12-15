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
        $this->timer = new Timer;

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

    public function time() {
        return $this->timer->span();
    }

    /**
     * Skip this test.
     *
     * @param null
     * @return null
     */
    public function skip() {
        if ($this->fn) {
            $this->skipped = true;
        }
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
    public function runnable() {
        return !$this->finished and
            !$this->skipped and
            !$this->pending;
    }
}