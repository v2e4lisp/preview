<?php
/**
 * Test World class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview;

class World {
    /**
     * Array of test suite objects without parent
     *
     * @var array $start_points
     */
    protected static $start_points = array();

    /**
     * array of TestSuite objects to track nested test suites.
     *
     * @var array $testsuite_chain
     */
    protected static $testsuite_chain = array();

    /**
     * all test groups
     *
     * @var array $groups
     */
    public static $groups = array();

    /**
     * Get current test suite
     *
     * @param null
     * @return object|null
     */
    public static function current() {
        if (empty(self::$testsuite_chain)) {
            return null;
        }
        return end(self::$testsuite_chain);
    }

    /**
     * Push test suite to $testsuite_chain.
     *
     * @param object $testsuite
     * @return null
     */
    public static function push($testsuite) {
        if (empty(self::$testsuite_chain)) {
            self::$start_points[] = $testsuite;
        }

        self::$testsuite_chain[] = $testsuite;
    }

    /**
     * Pop out a test suite from $testsuite_chain
     *
     * @param null
     * @return object|null
     */
    public static function pop() {
        return array_pop(self::$testsuite_chain);
    }

    /**
     * Run all the tests from $start_points.
     * the result of start point test suites will return
     *
     * @param null
     * @return array
     */
    public static function run() {
        $tests = static::filter_test_by_group();
        $runner = new Runner($tests);
        return $runner->run();
    }

    /**
     * add a test case/suite to group
     *
     * @param object $test TestCase/TestSuite object
     * @retrun string $group group name
     */
    public static function add_test_to_group($test, $group) {
        if(isset(self::$groups[$group])) {
            self::$groups[$group] = array();
        }
        self::$groups[$group][] = $test;
    }

    /**
     * filter tests by groups which is set in Configuration.
     *
     * @param null
     * @retrun array an array of test object.
     */
    private static function filter_test_by_group() {
        if (empty(Configuration::$test_groups)) {
            return self::$start_points;
        }

        $tests = array();
        foreach(Configuration::$test_groups as $group) {
            if(isset(self::$groups[$group])) {
                $tests = array_merge($tests, self::$groups[$group]);
            }
        }
        return array_unique($tests);
    }
}