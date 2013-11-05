<?php

class TestResultBase {
    protected $test;

    public function __construct($test) {
        $this->test = $test;
    }

    public function parent() {
        return $this->test->parent->result;
    }

    public function title() {
        return $this->test->title;
    }

    public function skipped() {
        return $this->test->skipped;
    }

    public function finished() {
        return $this->test->finished;
    }

    public function pending() {
        return $this->test->pending();
    }

    public function runable() {
        return $this->test->runable();
    }
}

class TestCaseResult extends TestResultBase {
    public function error() {
        return $this->test->error;
    }
}

class TestSuiteResult extends TestResultBase {
    private $_cases = null;

    private $_suites = null;

    public function cases() {
        if (isset($this->_suites)) {
            return $this->_cases;
        }

        $cases = array();
        foreach($this->test->cases as $case) {
            $cases[] = $case->result;
        }

        $this->_cases = $cases;
        return $cases;
    }

    public function suites() {
        if (isset($this->_suites)) {
            return $this->_suites;
        }

        $suites = array();
        foreach($this->test->suites as $suite) {
            $suites[] = $suite->result;
        }

        $this->_suites = $suites;
        return $suites;
    }
}