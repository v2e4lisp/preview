<?php
/**
 * Testify style dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\Testify;

use \Preview\Preview;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;
use \Preview\DSL\TestAPI;
use \Preview\DSL\Util;

class Suite {
    /**
     * TestSuite object
     *
     * @var object $suite
     */
    private $suite;

    /**
     * Test suite has been loaded.
     *
     * @var bool $loaded default is false
     */
    private $loaded = false;

    /**
     * Constructor
     * Accept multiple params in the form of
     * TITLE [, GROUP1, ... GROUPn]
     *
     * E.G :
     *
     * new Suite("title", "group1", "group2", "skip", "group3")
     *
     * this will create a TestSuite objcet titled by title,
     * belonging to group1, group2 and group3,
     * and marked as skipped.
     *
     * @param string $title
     * @param string
     * ...
     */
    public function __construct($title) {
        $groups = func_get_args();
        array_shift($groups);
        $this->suite = $this->create_suite($title, $groups);
    }

    /**
     * Add a before suite hook
     *
     * @param function $fn
     * @retrun $this
     */
    public function before($fn) {
        $this->suite->add_before_hook($fn);
        return $this;
    }

    /**
     * Add a after suite hook
     *
     * @param function $fn
     * @retrun $this
     */
    public function after($fn) {
        $this->suite->add_after_hook($fn);
        return $this;
    }

    /**
     * Add a before each hook. Invoked before running test case.
     *
     * @param function $fn
     * @retrun $this
     */
    public function before_each($fn) {
        $this->suite->add_before_each_hook($fn);
        return $this;
    }

    /**
     * Add a after each hook. Invoked after running test case.
     *
     * @param function $fn
     * @retrun $this
     */
    public function after_each($fn) {
        $this->suite->add_after_each_hook($fn);
        return $this;
    }

    /**
     * Add a test case to $this->suite
     * Accept multiple params in the form of
     * 1. TITLE [, GROUP1, GROUP2 .. [skip] .. GROUPn [, FUNCTION]]
     * 2. FUNCTION
     *
     * @retrun null
     */
    public function test() {
        $args = func_get_args();
        list($title, $groups, $fn) =
            $this->title_groups_and_callback($args);

        $case = new TestCase($title, $fn);
        if (!$fn) {
            Util::set_default_filename_and_lineno($case, debug_backtrace());
        }
        $this->suite->add($case);
        $this->add_test_to_groups_and_maybe_skip($case, $groups);
        return $this;
    }

    /**
     * Add child suite object.
     *
     * @param object $suite Testify\Suite object
     * @retrun $this
     */
    public function add_child($suite) {
        $suite->__set_parent_test_suite($this->suite);
        return $this;
    }

    /**
     * Set $this->suite's parent test suite.
     * __ implies that this method should not be call by
     * end user.
     *
     * @param object $testsuite a TestSuite object
     * @retrun $this
     */
    public function __set_parent_test_suite($testsuite) {
        $testsuite->add($this->suite);
        return $this;
    }

    /**
     * Load the test suite to current test world.
     *
     * @param null
     * @retrun null
     */
    public function load() {
        if ($this->loaded) {
            return false;
        }
        $this->loaded = true;
        Preview::$world->pop();
        Preview::$world->push($this->suite);
    }

    /**
     * Create a TestSuite object,
     * add it to groups if there's any and
     * skip it if "skip" specified.
     *
     * @param string $param
     * @retrun null
     */
    private function create_suite($title, $groups) {
        $suite = new TestSuite($title, function(){});
        Util::set_default_filename_and_lineno($suite, debug_backtrace());
        $this->add_test_to_groups_and_maybe_skip($suite, $groups);
        return $suite;
    }

    /**
     * Add test to groups
     * and if "skip" is specified, make this test skipped
     *
     * @param object $test TestSuite/TestCase object
     * @param array $groups an array of strings
     * @retrun null
     */
    private function add_test_to_groups_and_maybe_skip($test, $groups) {
        foreach($groups as $group) {
            if ($group == "skip") {
                $test->skip();
            } else {
                $test->add_to_group($group);
            }
        }
    }

    /**
     * Extract title, groups and callback function
     * "skip" is special group which make the test skipped
     *
     * @param array $params
     * @retrun null
     */
    private function title_groups_and_callback($params) {
        if (count($params) == 1) {
            if ($params[0] instanceof \Closure) {
                return array("", array(), $params[0]);
            }
            return array($params[0], array(), null);
        }

        $fn = end($params);
        if ($fn instanceof \Closure) {
            array_pop($params);
        } else {
            $fn = null;
        }

        $title = array_shift($params);
        return array($title, $params, $fn);
    }
}