<?php
/**
 * bdd style dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\BDD;

use \Preview\World;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;
use \Preview\DSL\TestAPI;

/**
 * Start a test suite.
 *
 * @param string $title A string to describe this test suite.
 * @param function $fn Default is null(which means pending).
 * @return object TestSuite object
 */
function describe($title, $fn=null) {
    $desc = new TestSuite($title, $fn);
    $desc->set_parent(World::current());
    World::push($desc);
    $desc->setup();
    World::pop();
    return new TestAPI($desc);
}

/**
 * An alias for describe
 *
 * @param string $title A string to describe a certain situation,
 *                      typically starts with 'when'.
 * @param function $fn Default is null(which means pending).
 * @return object TestSuite object.
 */
function context($title, $fn=null) {
    return describe($title, $fn);
}

/**
 * Start a test case.
 *
 * @param string $title A string to describe this test case.
 * @param function $fn Default is null(which means pending).
 * @return object TestCase object.
 */
function it($title, $fn=null) {
    $case = new TestCase($title, $fn);
    World::current()->add($case);
    return new TestAPI($case);
}

/**
 * Current test suite first run this hook before any test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function before($fn) {
    World::current()->before($fn);
}

/**
 * Current test suite run this hook after all test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function after($fn) {
    World::current()->after($fn);
}

/**
 * A hook to be called before running each test case in current test suite.
 *
 * @param function $fn
 * @retrun null
 */
function before_each($fn) {
    World::current()->before_each($fn);
}

/**
 * A hook to be called after running each test case in current test suite.
 *
 * @param string $param
 * @retrun null
 */
function after_each($fn) {
    World::current()->before_after($fn);
}