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

    public function done() {
        return $this->test->done;
    }
}

class TestCaseResult extends TestResultBase {
    public function error() {
        return $this->test->error;
    }
}

class TestSuiteResult extends TestResultBase {
    public function cases() {
        $cases = array();
        foreach($this->test->cases as $case) {
            $cases[] = $case->result;
        }
        return $cases;
    }

    public function suites() {
        $suites = array();
        foreach($this->test->suites as $suite) {
            $suites[] = $suite->result;
        }
        return $suites;
    }
}