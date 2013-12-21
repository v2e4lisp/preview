<?php
/**
 * Qunit like dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\Qunit;

use \Preview\World;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;

/**
 * start a test suite
 *
 * @param string $title
 * @retrun object testsuite object.
 */
function suite($title) {
    $desc = new TestSuite($title, function(){});
    World::pop();
    World::push($desc);
    return $desc;
}

/**
 * create a test case
 *
 * @param string $title
 * @param function|null $fn
 * @retrun object testcase object
 */
function test($title, $fn=null) {
    if (empty($fn) and $title instanceof \Closure) {
        $fn = $title;
        $title = "";
    }
    $case = new TestCase($title, $fn);
    $case->set_parent(World::current());
    return $case;
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