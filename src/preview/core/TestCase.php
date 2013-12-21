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
    /**
     * context to this test and all related hooks.
     *
     * @var object|null $context
     */
    protected $context = null;

    public function __construct($title, $fn) {
        parent::__construct($title, $fn);
        $this->result = new TestCaseResult($this);
        $this->context = new \stdClass;
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
        if (!$this->runnable()) {
            return;
        }
        $this->timer->start();
        Configuration::reporter()->before_case($this->result);
        $this->parent->run_before_each($this->context);

        try {
            $this->fn->bindTo($this->context, $this->context)->__invoke();
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
        Configuration::reporter()->after_case($this->result);
        $this->timer->stop();
    }

    /**
     * check if the test case is in test group
     *
     * @param null
     * @retrun bool
     */
    public function in_test_group() {
        if (!is_null($this->in_test_group)) {
            return $this->in_test_group;
        }

        $groups = Configuration::$test_groups;
        $this->in_test_group = false;
        foreach ($groups as $group) {
            if ($this->in_group($group)) {
                $this->in_test_group = true;
            }
        }

        return $this->in_test_group;
    }
}