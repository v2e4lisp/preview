<?php
/**
 * Base class for test result.
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Result;

class TestBase {
    /**
     * Test object
     *
     * @var object $test
     */
    protected $test;

    public function __construct($test) {
        $this->test = $test;
    }

    /**
     * Get parent test result object
     *
     * @param null
     * @return object|null
     */
    public function parent() {
        if ($this->test->parent) {
            return $this->test->parent->result;
        }

        return null;
    }

    /**
     * Get test filename
     *
     * @param null
     * @return string
     */
    public function filename() {
        return $this->test->filename;
    }

    /**
     * Get test's start line number.
     * If the test is pending return null.
     *
     * @param null
     * @return int|null
     */
    public function startline() {
        return $this->test->startline;
    }

    /**
     * Get test's end line number.
     * If the test is pending return null.
     *
     * @param null
     * @return int|null
     */
    public function endline() {
        return $this->test->endline;
    }

    /**
     * Get the test's title
     *
     * @param null
     * @return string
     */
    public function title() {
        if(empty($this->test->title)) {
            return "No description";
        }

        return $this->test->title;
    }

    /**
     * Check if the test is passed
     *
     * @param null
     * @return bool
     */
    public function passed() {
        return $this->finished() and
            !$this->error() and
            !$this->failed();
    }

    /**
     * Get failure exception.
     * if failed an exception object will be returned
     * otherwise null.
     *
     * @param null
     * @return object|null
     */
    public function failed() {
        return $this->test->failure;
    }

    /**
     * Get exception for error test.
     * Return null if this test case does not have any error.
     *
     * @param null
     * @return object|null
     */
    public function error() {
        return $this->test->error;
    }

    /**
     * Get error exception or failure exception if there's any.
     *
     * @param null
     * @retrun object|null error/failure exception object
     */
    public function error_or_failed() {
        if ($this->error()) {
            return $this->error();
        }

        return $this->failed();
    }

    /**
     * Check if the test is skipped.
     *
     * @param null
     * @return bool
     */
    public function skipped() {
        return $this->test->skipped;
    }

    /**
     * Check if the test is pending.
     *
     * @param null
     * @return bool
     */
    public function pending() {
        return $this->test->pending;
    }

    /**
     * Check if test is skipped or pending
     *
     * @param null
     * @retrun bool
     */
    public function skipped_or_pending() {
        return $this->skipped() or $this->pending();
    }

    /**
     * Check if the test is finished.
     *
     * @param null
     * @return bool
     */
    public function finished() {
        return $this->test->finished;
    }

    /**
     * Check if the test is runnable.
     * Test is runnable if test is neither finished, skipped nor pending.
     *
     * @param null
     * @return bool
     */
    public function runnable() {
        return $this->test->runnable();
    }

    /**
     * Get running time.
     *
     * @param null
     * @return float timespan/seconds.
     */
    public function time() {
        return $this->test->time();
    }


    /**
     * Get groups this test belongs to
     *
     * @param null
     * @retrun array array of groups' name
     */
    public function groups() {
        return $this->test->groups;
    }

    /**
     * get title chain as an array;
     *
     * @param null
     * @retrun array array of title(string);
     */
    public function titles() {
        $titles = array($this->title());
        $suite = $this->parent();
        while($suite) {
            $titles[] = $suite->title();
            $suite = $suite->parent();
        }

        return array_reverse($titles);
    }

    /**
     * get all titles as an string .
     *
     * @param null
     * @retrun string
     */
    public function full_title() {
        return implode($this->titles(), " ");
    }
}