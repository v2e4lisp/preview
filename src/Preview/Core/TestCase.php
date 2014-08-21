<?php

namespace Preview\Core;

use \Preview\Preview;
use \Preview\Result\TestCase as TestCaseResult;

/**
 * Test Case class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */
class TestCase extends TestBase {
    /**
     * constructor
     *
     * @param string $title
     * @param function $fn
     */
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
     * force test to be in error state
     *
     * @param object $error an exception object
     * @retrun null
     */
    public function force_error($error) {
        if (!$error) {
            return;
        }

        $this->reset_state();
        $this->finished = true;
        $this->error = $error;
        return $error;
    }

    /**
     * Run the test.
     *
     * 1. Call Reporter's before_case method
     * 2. Run real test code
     * 3. Call Reporter's after_case method
     * 4. check if fast-fail
     *
     * @param null
     * @return null
     */
    public function run() {
        Preview::$config->reporter->before_case($this->result);

        if ($this->runnable()) {
            $this->timer->start();
            $this->extend_context_with_parent();
            $this->run_test();
            $this->finish();
            $this->timer->stop();
        }

        Preview::$config->reporter->after_case($this->result);

        if (Preview::$config->fail_fast) {
            $this->exit_if_error_or_failure();
        }
    }

    /**
     * Run the real test body
     *
     * 1. before each hooks
     * 2. test content itself
     * 3. after each hooks
     *
     * @param null
     * @retrun null
     */
    private function run_test() {
        try {
            $this->parent->run_before_each($this->context);
            $this->invoke_closure_with_context($this->fn,
                                               $this->context);
        } catch (\ErrorException $error) {
            $this->set_error($error);
        } catch (\Exception $e) {
            foreach(Preview::$config->assertion_exceptions as $klass) {
                if ($e instanceof $klass) {
                    $this->set_failure($e);
                    break;
                }
            }

            if (!$this->failure) {
                $this->set_error($e);
            }
        }

        $this->parent->run_after_each($this->context);
    }

    /**
     * Exit this test world if error or failure occurred.
     *
     * @param null
     * @retrun null
     */
    private function exit_if_error_or_failure() {
        if ($this->error or $this->failure) {
            Preview::$world->force_exit(1);
        }
    }
}