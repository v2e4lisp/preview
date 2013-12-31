<?php
/**
 * tdd style dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\TDD;

use \Preview\Preview;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;
use \Preview\DSL\TestAPI;

/**
 * create a test suite.
 *
 * @param string $title A string to describe this test suite.
 * @param function $fn Default is null(which means suite is pending).
 * @return object TestSuite object
 */
function suite($title, $fn=null) {
    $desc = new TestSuite($title, $fn);
    $desc->set_parent(Preview::$world->current());
    Preview::$world->push($desc);
    $desc->setup();
    Preview::$world->pop();
    return new TestAPI($desc);
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
    Preview::$world->current()->add($case);
    return new TestAPI($case);
}

/**
 * Current test suite first run this hook before any test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function suite_setup($fn) {
    Preview::$world->current()->add_before_hook($fn);
}

/**
 * Current test suite run this hook after all test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function suite_teardown($fn) {
    Preview::$world->current()->add_after_hook($fn);
}

/**
 * A hook to be called before running each test case in current test suite.
 *
 * @param function $fn
 * @retrun null
 */
function setup($fn) {
    Preview::$world->current()->add_before_each_hook($fn);
}

/**
 * A hook to be called after running each test case in current test suite.
 *
 * @param string $param
 * @retrun null
 */
function teardown($fn) {
    Preview::$world->current()->add_after_each_hook($fn);
}