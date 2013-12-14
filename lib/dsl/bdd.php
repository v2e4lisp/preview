<?php

namespace Preview\DSL\BDD;

require_once __DIR__.'/../world.php';
require_once __DIR__.'/../core.php';

use \Preview\World;
use \Preview\TestSuite;
use \Preview\TestCase;

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
    return $desc;
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
    $case->set_parent(World::current());
    return $case;
}

/**
 * Current test suite first run this hook before any test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function before($fn) {
    World::current()->before_hooks[] = $fn;
}

/**
 * Current test suite run this hook after all test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function after($fn) {
    World::current()->after_hooks[] = $fn;
}

/**
 * A hook to be called before running each test case in current test suite.
 *
 * @param function $fn
 * @retrun null
 */
function before_each($fn) {
    World::current()->before_each_hooks[] = $fn;
}

/**
 * A hook to be called after running each test case in current test suite.
 *
 * @param string $param
 * @retrun null
 */
function after_each($fn) {
    World::current()->before_after_hooks[] = $fn;
}