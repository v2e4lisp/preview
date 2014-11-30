<?php
/**
 * Sest suite result class
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
     * @var array|null $cases
     */
    protected $cases = null;

    /**
     * Children test suites' results
     * It's a memoization for $this->suites().
     *
     * @var array|null $suites
     */
    protected $suites = null;

    /**
     * all cases in this test suite (recursively)
     *
     * @var array|null $all_cases;
     */
    protected $all_cases = null;

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

        $this->cases = $cases;
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

        $this->suites = $suites;
        return $suites;
    }

    /**
     * Recursively get all cases in this suite
     *
     * @param null
     * @return array array of test case result object.
     */
    public function all_cases() {
        if (isset($this->all_cases)) {
            return $this->all_cases;
        }

        $this->all_cases = $this->cases();
        $suites = $this->suites();
        foreach($suites as $suite) {
            $this->all_cases = array_merge($this->all_cases, $suite->all_cases());
        }

        return $this->all_cases;
    }
}
