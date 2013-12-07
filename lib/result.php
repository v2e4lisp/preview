<?php
/**
 * Test result classes
 *
 * @package Preview
 * @author Wenjun Yan
 */

namespace Preview;

/**
 * Base class for TestSuiteResult and TestCaseResult.
 */
class TestResultBase {
    /**
     * Test object
     *
     * @var object $test
     */
    protected $test;

    public function __construct($test) {
        $this->test = $test;
    }

    /**
     * Get parent test result object
     *
     * @param null
     * @retrun object|null
     */
    public function parent() {
        if ($this->test->parent) {
            return $this->test->parent->result;
        }

        return null;
    }

    /**
     * Get test filename
     *
     * @param null
     * @retrun string
     */
    public function filename() {
        return $this->test->filename;
    }

    /**
     * Get test's start line number.
     * If the test is pending return null.
     *
     * @param null
     * @retrun int|null
     */
    public function startline() {
        return $this->test->startline;
    }

    /**
     * Get test's end line number.
     * If the test is pending return null.
     *
     * @param null
     * @retrun int|null
     */
    public function endline() {
        return $this->test->endline;
    }

    /**
     * Get title of the test.
     *
     * @param null
     * @retrun string
     */
    public function title() {
        return $this->test->title;
    }

    /**
     * Check test is skipped.
     *
     * @param null
     * @retrun bool
     */
    public function skipped() {
        return $this->test->skipped;
    }

    /**
     * Check test is finished.
     *
     * @param null
     * @retrun bool
     */
    public function finished() {
        return $this->test->finished;
    }

    /**
     * Check test is pending.
     *
     * @param null
     * @retrun bool
     */
    public function pending() {
        return $this->test->pending;
    }

    /**
     * Check test is pending.
     *
     * @param null
     * @retrun bool
     */
    public function runable() {
        return $this->test->runable();
    }
}


/**
 * TestCaseResult class
 */
class TestCaseResult extends TestResultBase {
    /**
     * Get exception object from the test case.
     * Return null if this test case is passed.
     *
     * @param null
     * @retrun object|null
     */
    public function error() {
        return $this->test->error;
    }
}

/**
 * TestSuiteResult class
 */
class TestSuiteResult extends TestResultBase {
    /**
     * results of Children test cases
     * It's a memoization for $this->cases().
     *
     * @var array|null $_cases
     */
    protected $_cases = null;

    /**
     * Children test suites' results
     * It's a memoization for $this->suites().
     *
     * @var array|null $_cases
     */
    protected $_suites = null;

    /**
     * Get results of children test cases
     *
     * @param null
     * @retrun array
     */
    public function cases() {
        if (isset($this->_cases)) {
            return $this->_cases;
        }

        $cases = array();
        foreach($this->test->cases as $case) {
            $cases[] = $case->result;
        }

        $this->_cases = $cases;
        return $cases;
    }

    /**
     * Get results of children test suites
     *
     * @param null
     * @retrun array
     */
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