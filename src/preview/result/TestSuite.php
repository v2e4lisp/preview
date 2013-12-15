<?php
/**
 * test suite result class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Result;

class TestSuite extends TestBase {
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
     * @return array
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
     * @return array
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