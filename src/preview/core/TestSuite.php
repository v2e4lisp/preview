<?php
/**
 * Test suite class
 * It contains children test cases and test suites.
 * Test suites can be nested.
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Core;

use Preview\Configuration;
use Preview\Result\TestSuite as TestSuiteResult;

class TestSuite extends TestBase {
    /**
     * before hooks for itself.
     *
     * @var array $before_hooks
     */
    public $before_hooks = array();

    /**
     * before hooks for children test cases
     *
     * @var array $before_each_hooks
     */
    public $before_each_hooks = array();

    /**
     * after hooks for itself
     *
     * @var array $after_hooks
     */
    public $after_hooks = array();

    /**
     * after hooks for children test cases
     *
     * @var array $after_each_hooks
     */
    public $after_each_hooks = array();

    /**
     * children test suite objects
     *
     * @var array $suites
     */
    public $suites = array();

    /**
     * children test case objects
     *
     * @var array $cases
     */
    public $cases = array();

    public function __construct($title, $fn) {
        parent::__construct($title, $fn);
        $this->result = new TestSuiteResult($this);
    }

    /**
     * Skip this test. recursively mark all its children to be skipped.
     *
     * @param null
     * @retrun null
     */
    public function skip() {
        parent::skip();
        foreach ($this->suites as $suite) {
            $suite->skip();
        }
        foreach ($this->cases as $case) {
            $case->skip();
        }
    }

    /**
     * set parent test suite.
     *
     * @param object|null $suite: parent test suite
     * @return null
     */
    public function set_parent($suite) {
        // if empty($suite), that's a start point of the TEST-WORLD.
        if (!empty($suite)) {
            $suite->suites[] = $this;
            $this->parent = $suite;
        }
    }

    /**
     * Invoke $fn and randomize children cases and suites.
     *
     * @param null
     * @return null
     */
    public function setup() {
        if ($this->pending) {
            return;
        }

        $this->fn->__invoke();
        shuffle($this->cases);
        shuffle($this->suites);
    }

    /**
     * Run this suites.
     * 1. Call Reporter's before_suite method.
     * 2. Run before hooks.
     * 3. Run children test cases and suites.
     * 4. Run after hooks.
     * 5. Call Reporters's after_suite method.
     *
     * @param null
     * @return null
     */
    public function run() {
        if (!$this->runnable()) {
            return;
        }

        $this->timer->start();
        Configuration::reporter()->before_suite($this->result);
        $this->run_before();
        foreach ($this->cases as $case) {
            $case->run();
        }
        foreach ($this->suites as $suite) {
            $suite->run();
        }
        $this->finish();
        $this->run_after();
        Configuration::reporter()->after_suite($this->result);
        $this->timer->stop();
    }

    /**
     * Run before hooks.
     *
     * @param null
     * @return null
     */
    public function run_before() {
        foreach ($this->before_hooks as $before) {
            $before->__invoke();
        }
    }

    /**
     * Run children cases' before hooks
     * This method should be called only by its children test cases.
     *
     * @param null
     * @return null
     */
    public function run_before_each($context) {
        if ($this->parent) {
            $this->parent->run_before_each($context);
        }

        foreach ($this->before_each_hooks as $before) {
            $fn = $before->bindTo($context, $context)->__invoke();
        }
    }

    /**
     * run after hooks
     *
     * @param null
     * @return null
     */
    public function run_after() {
        foreach ($this->after_hooks as $after) {
            $after->__invoke();
        }
    }

    /**
     * Run children test cases' after hooks
     * This method should be called only by its children test cases.
     *
     * @param null
     * @return null
     */
    public function run_after_each($context) {
        if ($this->parent) {
            $this->parent->run_after_each($context);
        }

        foreach ($this->after_each_hooks as $after) {
            $after->bindTo($context, $context)->__invoke();
        }
    }
}
