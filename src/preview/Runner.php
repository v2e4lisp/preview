<?php
/**
 * Test Runner class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview;

class Runner {
    /**
     * Top-level test suites
     *
     * @var array $start_points
     */
    protected $start_points = array();

    public function __construct($start_points) {
        $this->start_points = $start_points;
        shuffle($this->start_points);
    }

    /**
     * run the top-level test suites and recursively run all test cases.
     *
     * @param null
     * @retrun array array of test result objects.
     */
    public function run() {
        $results = array();
        foreach($this->start_points as $point) {
            $results[] = $point->result;
        }

        Configuration::reporter()->before_all($results);
        foreach($this->start_points as $point) {
            $point->run();
        }
        Configuration::reporter()->after_all($results);

        return $results;
    }
}