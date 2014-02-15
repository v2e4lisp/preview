<?php

namespace Preview\Core;

use Preview\Preview;
use Preview\Result\TestSuite as TestSuiteResult;

/**
 * Test suite class
 * It contains children test cases and test suites.
 * Test suites can be nested.
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */
class TestSuite extends TestBase {
    /**
     * before hooks for itself.
     *
     * @var array $before_hooks
     */
    protected $before_hooks = array();

    /**
     * before hooks for children test cases
     *
     * @var array $before_each_hooks
     */
    protected $before_each_hooks = array();

    /**
     * after hooks for itself
     *
     * @var array $after_hooks
     */
    protected $after_hooks = array();

    /**
     * after hooks for children test cases
     *
     * @var array $after_each_hooks
     */
    protected $after_each_hooks = array();

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

    /**
     * constructor
     *
     * @param string $title
     * @param function $fn
     */
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
     * Recursively force itself and its children suites/cases
     * in error state.
     *
     * @param string $param
     * @retrun null
     */
    public function force_error($error) {
        if (!$error) {
            return;
        }

        $this->reset_state();
        $this->finished = true;
        $this->set_error($error);

        foreach($this->suites as $suite) {
            $suite->force_error($error);
        }

        foreach($this->cases as $case) {
            $case->force_error($error);
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
     * Add child test suite/case
     *
     * @param object $suite_or_case
     * @retrun object $this
     */
    public function add($suite_or_case) {
        $suite_or_case->set_parent($this);
        return $this;
    }

    /**
     * Remove child test suite/case
     *
     * @param object $suite_or_case TestSuite/TestCase object
     * @retrun object|null when no such test to delete return null.
     */
    public function remove($suite_or_case) {
        if ($suite_or_case instanceof self) {
            return $this->remove_from($suite_or_case, $this->suites);
        }
        return $this->remove_from($suite_or_case, $this->cases);
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
        Preview::$config->reporter->before_suite($this->result);

        if ($this->runnable()) {
            $this->timer->start();
            $this->extend_context_with_parent();

            // run before hooks if error occured
            // force all its children tests set to error.
            try {
                $this->run_before();
            } catch (\Exception $e) {
                $this->force_error($e);
            }

            // run all its children test cases/suites.
            foreach ($this->cases as $case) {
                $case->run();
            }
            foreach ($this->suites as $suite) {
                $suite->run();
            }

            // run after hooks.
            // do not handle any exceptions,
            // since reporter has printed results out.
            $this->run_after();
            $this->finish();

            $this->timer->stop();
        }

        Preview::$config->reporter->after_suite($this->result);
    }

    /**
     * Run before hooks.
     *
     * @param null
     * @return null
     */
    public function run_before() {
        if (Preview::$config->before_hook instanceof \Closure) {
            $this->invoke_closure_with_context(
                Preview::$config->before_hook, $this->context);
        }

        foreach ($this->before_hooks as $before) {
            $this->invoke_closure_with_context($before, $this->context);
        }
    }

    /**
     * Run children cases' before hooks
     * This method should be called only by its children test cases.
     *
     * @param stdClass $context case context object
     * @param bool $global whether run config->before_each_hook,
     *                     default true
     * @return null
     */
    public function run_before_each($context, $global=true) {
        if ($global) {
            if (Preview::$config->before_each_hook instanceof \Closure) {
                $this->invoke_closure_with_context(
                    Preview::$config->before_each_hook, $context);
            }
        }

        if ($this->parent) {
            $this->parent->run_before_each($context, false);
        }

        foreach ($this->before_each_hooks as $before) {
            $this->invoke_closure_with_context($before, $context);
        }
    }

    /**
     * run after hooks
     *
     * @param null
     * @return null
     */
    public function run_after() {
        if (Preview::$config->after_hook instanceof \Closure) {
            $this->invoke_closure_with_context(
                Preview::$config->after_hook, $this->context);
        }

        foreach ($this->after_hooks as $after) {
            $this->invoke_closure_with_context($after, $this->context);
        }
    }

    /**
     * Run children test cases' after hooks
     * This method should be called only by its children test cases.
     *
     *
     * @param stdClass $context case context object
     * @param bool $global whether run config->before_each_hook,
     *                     default true
     * @return null
     */
    public function run_after_each($context, $global=true) {
        if ($global) {
            if (Preview::$config->after_each_hook instanceof \Closure) {
                $this->invoke_closure_with_context(
                    Preview::$config->after_each_hook, $context);
            }
        }

        if ($this->parent) {
            $this->parent->run_after_each($context, false);
        }

        foreach ($this->after_each_hooks as $after) {
            $this->invoke_closure_with_context($after, $context);
        }
    }

    /**
     * add before hooks.
     *
     * @param function $fn
     * @retrun $this
     */
    public function add_before_hook($fn) {
        $this->before_hooks[] = $fn;
        return $this;
    }

    /**
     * add after hooks.
     *
     * @param function $fn
     * @retrun $this
     */
    public function add_after_hook($fn) {
        $this->after_hooks[] = $fn;
        return $this;
    }

    /**
     * add before_each hooks.
     *
     * @param function $fn
     * @retrun $this
     */
    public function add_before_each_hook($fn) {
        $this->before_each_hooks[] = $fn;
        return $this;
    }

    /**
     * add after_each hooks.
     *
     * @param function $fn
     * @retrun $this
     */
    public function add_after_each_hook($fn) {
        $this->after_each_hooks[] = $fn;
        return $this;
    }

    /**
     * Remove case/suite from $this->cases/$this->suites
     *
     * @param object $suite_or_case TestSuite/TestCase object
     * @param array &$collection $this->suites/$this->cases passed by ref.
     * @retrun object|null removed case/suite or null
     */
    protected function remove_from($suite_or_case, &$collection) {
        $key = array_search($suite_or_case, $collection, true);
        if ($key !== false) {
            unset($collection[$key]);
            return $suite_or_case;
        }
    }
}
