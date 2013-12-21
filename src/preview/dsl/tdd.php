<?php
/**
 * tdd style dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\TDD;

use \Preview\World;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;

/**
 * create a test suite.
 *
 * @param string $title A string to describe this test suite.
 * @param function $fn Default is null(which means suite is pending).
 * @return object TestSuite object
 */
function suite($title, $fn=null) {
    $desc = new TestSuite($title, $fn);
    $desc->set_parent(World::current());
    World::push($desc);
    $desc->setup();
    World::pop();
    return $desc;
}

/**
 * create a test case.
 *
 * @param string $title A string to describe this test case.
 * @param function $fn Default is null(which means testcase is pending).
 * @return object TestCase object.
 */
function test($title, $fn=null) {
    $case = new TestCase($title, $fn);
    World::current()->add($case);
    return $case;
}

/**
 * Current test suite first run this hook before any test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function suite_setup($fn) {
    World::current()->before($fn);
}

/**
 * Current test suite run this hook after all test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function suite_teardown($fn) {
    World::current()->after($fn);
}

/**
 * A hook to be called before running each test case in current test suite.
 *
 * @param function $fn
 * @retrun null
 */
function setup($fn) {
    World::current()->before_each($fn);
}

/**
 * A hook to be called after running each test case in current test suite.
 *
 * @param string $param
 * @retrun null
 */
function teardown($fn) {
    World::current()->before_after($fn);
}