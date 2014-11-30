<?php
/**
 * Radomized Filter
 * Randomize tests.
 *
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 * @package Preview
 */

namespace Preview\Filter;

class Randomized extends Base {

    /**
     * Randomize all test suites.
     *
     * @param array $suites an array of TestSuite object
     * @return arrray an array of TestSuite object
     */
    public function run($suites) {
        shuffle($suites);
        foreach ($suites as $suite) {
            $this->randomize($suite);
        }

        return $suites;
    }

    /**
     * Recursively randomize a TestSuite's children tests
     *
     * @param string $param
     * @return null
     */
    protected function randomize($suite) {
        shuffle($suite->cases);
        shuffle($suite->suites);

        foreach ($suite->suites as $child_suite) {
            $this->randomize($child_suite);
        }
    }
}
