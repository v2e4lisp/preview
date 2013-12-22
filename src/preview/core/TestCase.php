<?php
/**
 * Test Case class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Core;

use \Preview\Configuration;
use \Preview\Result\TestCase as TestCaseResult;

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
        Configuration::reporter()->before_case($this->result);

        if ($this->runnable()) {
            $this->timer->start();

            $this->context = (object) array_merge((array) $this->context,
                                                  (array) $this->parent->context);
            $this->parent->run_before_each($this->context);
            try {
                $this->invoke_closure_with_context($this->fn, $this->context);
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
            $this->parent->run_after_each($this->context);
            $this->finish();

            $this->timer->stop();
        }

        Configuration::reporter()->after_case($this->result);
    }
}