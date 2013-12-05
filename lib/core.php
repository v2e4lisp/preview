<?php

namespace Preview;

require_once 'result.php';
require_once 'configuration.php';

class TestBase {
    public $parent = null;

    public $skipped = false;

    public $finished = false;

    public $pending = false;

    public $title;

    public $result;

    public $filename = null;

    public $startline = null;

    public $endline = null;

    protected $fn;

    public function __construct($title, $fn) {
        $this->title = $title;
        $this->fn = $fn;
        $this->pending = !isset($fn);

        if ($this->fn) {
            $ref = new \ReflectionFunction($this->fn);
            $this->filename = $ref->getFileName();
            $this->startline = $ref->getStartLine();
            $this->endline = $ref->getEndLine();
        }

    }

    public function skip() {
        $this->skipped = true;
    }

    public function finish() {
        $this->finished = true;
    }

    public function runable() {
        return !$this->finished and
            !$this->skipped and
            !$this->pending;
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
        if (!$this->runable()) {
            return;
        }

        Configuration::$reporter->before_case($this->result);
        $this->parent->run_before_each();

        if (empty(Configuration::$assertion_errors)) {
            $this->run_with_false();
        } else {
            $this->run_with_exception();
        }

        $this->parent->run_after_each();
        $this->finish();
        Configuration::$reporter->after_case($this->result);
    }

    private function run_with_false() {
        if (!$this->fn->__invoke()) {
            $this->error = new \Exception("failed");
        }
    }

    private function run_with_exception() {
        try {
            $this->fn->__invoke();
        } catch (\Exception $e) {
            foreach(Configuration::$assertion_errors as $klass) {
                if ($e instanceof $klass) {
                    $this->error = $e;
                }
            }

            if (!$this->error) {
                throw $e;
            }
        }
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
        if ($this->pending) {
            return;
        }

        $this->fn->__invoke();
        shuffle($this->cases);
        shuffle($this->suites);
    }

    public function run() {
        if (!$this->runable()) {
            return;
        }

        Configuration::$reporter->before_suite($this->result);
        $this->run_before();
        foreach ($this->cases as $case) {
            $case->run();
        }
        foreach ($this->suites as $suite) {
            $suite->run();
        }
        $this->finish();
        $this->run_after();
        Configuration::$reporter->after_suite($this->result);
    }

    public function run_before() {
        foreach ($this->before_hooks as $before) {
            $before->__invoke();
        }
    }

    public function run_before_each() {
        if ($this->parent) {
            $this->parent->run_before_each();
        }

        foreach ($this->before_each_hooks as $before) {
            $before->__invoke();
        }
    }

    public function run_after() {
        foreach ($this->after_hooks as $after) {
            $after->__invoke();
        }
    }

    public function run_after_each() {
        if ($this->parent) {
            $this->parent->run_after_each();
        }

        foreach ($this->after_each_hooks as $after) {
            $after->__invoke();
        }
    }
}