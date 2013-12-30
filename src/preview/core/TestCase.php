<?php
/**
 * Test Case class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Core;

use \Preview\Preview;
use \Preview\Result\TestCase as TestCaseResult;

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
        Preview::$config->reporter->before_case($this->result);

        if ($this->runnable()) {
            $this->timer->start();
            $this->extend_context_with_parent();

            try {
                $this->parent->run_before_each($this->context);
                $this->invoke_closure_with_context($this->fn, $this->context);
                $this->parent->run_after_each($this->context);
            } catch (\ErrorException $error) {
                $this->set_error($error);
            } catch (\Exception $e) {
                foreach(Preview::$config->assertion_errors as $klass) {
                    if ($e instanceof $klass) {
                        $this->set_failure($e);
                        break;
                    }
                }

                if (!$this->failure) {
                    $this->set_error($e);
                }
            }

            $this->finish();
            $this->timer->stop();
        }

        Preview::$config->reporter->after_case($this->result);
    }
}