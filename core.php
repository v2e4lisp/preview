<?php

require_once './result.php';
require_once './configuration.php';

class TestBase {
    public $parent = null;

    public $skipped = false;

    public $finished = false;

    public $title;

    public $result;

    protected $fn;

    public function __construct($title, $fn) {
        $this->title = $title;
        $this->fn = $fn;
    }

    public function skip() {
        $this->skipped = true;
    }

    public function finish() {
        $this->finished = true;
    }
}

class TestCase extends TestBase {
    public $error = false;

    public function __construct($title, $fn) {
        parent::__construct($title, $fn);
        $this->result = new TestCaseResult($this);
    }

    public function set_parent($suite) {
        $this->parent = $suite;
        $suite->cases[] = $this;
    }

    public function run() {
        if ($this->finished or $this->skipped) {
            return;
        }

        Configuration::$reporter->before_case($this->result);
        try {
            $this->parent->run_before();
            $this->fn->__invoke();
            $this->parent->run_after();
        } catch (Exception $e) {
            foreach(Configuration::$assertion_errors as $klass) {
                if ($e instanceof $klass) {
                    $this->error = $e;
                }
            }

            if (!$this->error) {
                throw $e;
            }
        }
        $this->finish();
        Configuration::$reporter->after_case($this->result);
    }
}

class TestSuite extends TestBase {
    public $before_hooks = array();

    public $before_each_hooks = array();

    public $after_hooks = array();

    public $after_each_hooks = array();

    public $suites = array();

    public $cases = array();

    public function __construct($title, $fn) {
        parent::__construct($title, $fn);
        $this->result = new TestSuiteResult($this);
    }

    public function set_parent($suite) {
        // if empty($suite), that's a start point of the TEST-WORLD.
        if (!empty($suite)) {
            $suite->suites[] = $this;
            $this->parent = $suite;
        }
    }

    public function setup() {
        $this->fn->__invoke();
        shuffle($this->cases);
        shuffle($this->suites);
    }

    public function run() {
        if ($this->finished or $this->skipped) {
            return;
        }

        Configuration::$reporter->before_suite($this->result);
        foreach ($this->cases as $case) {
            $case->run();
        }
        foreach ($this->suites as $suite) {
            $suite->run();
        }
        $this->finish();
        Configuration::$reporter->after_suite($this->result);
    }

    public function run_before() {
        if ($this->parent) {
            $this->parent->run_before();
        }

        foreach ($this->before_hooks as $before) {
            $before->__invoke();
        }

        foreach ($this->before_each_hooks as $before) {
            $before->__invoke();
        }
    }

    public function run_after() {
        if ($this->parent) {
            $this->parent->run_after();
        }

        foreach ($this->after_hooks as $after) {
            $after->__invoke();
        }

        foreach ($this->after_each_hooks as $after) {
            $after->__invoke();
        }
    }
}