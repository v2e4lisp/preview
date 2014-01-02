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

class Suite {
    private $suite;

    private $children = array();

    private $loaded = false;

    public function __construct($title) {
        $groups = func_get_args();
        array_shift($groups);
        $this->suite = $this->create_suite($title, $groups);
    }

    public function before($fn) {
        $this->suite->add_before_hook($fn);
        return $this;
    }

    public function after($fn) {
        $this->suite->add_after_hook($fn);
        return $this;
    }

    public function before_each($fn) {
        $this->suite->add_before_each_hook($fn);
        return $this;
    }

    public function after_each($fn) {
        $this->suite->add_after_each_hook($fn);
        return $this;
    }

    public function test() {
        $args = func_get_args();
        list($title, $groups, $fn) =
            $this->title_groups_and_callback($args);

        $case = new TestCase($title, $fn);
        $this->suite->add($case);
        $this->add_test_to_groups_and_maybe_skip($case, $groups);
        return $this;
    }

    public function add_child_suite($suite) {
        $suite->__set_parent_test_suite($this->suite);
        return $this;
    }

    public function __set_parent_test_suite($testsuite) {
        $testsuite->add($this->suite);
        return $this;
    }

    public function load() {
        if ($this->loaded) {
            return false;
        }
        $this->loaded = true;
        Preview::$world->push($this->suite);
        Preview::$world->pop();
    }

    private function create_suite($title, $groups) {
        $suite = new TestSuite($title, function(){});
        $this->add_test_to_groups_and_maybe_skip($suite, $groups);
        return $suite;
    }

    private function add_test_to_groups_and_maybe_skip($test, $groups) {
        foreach($groups as $group) {
            if ($group == "skip") {
                $test->skip();
            } else {
                $test->add_to_group($group);
            }
        }
    }

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