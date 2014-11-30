<?php
/**
 * Qunit like dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\Qunit;

use \Preview\Preview;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;
use \Preview\DSL\TestAPI;
use \Preview\DSL\Util;

/**
 * start a test suite
 *
 * @param string $title
 * @return object TestAPI object.
 */
function suite($title) {
    $desc = new TestSuite($title, function(){});
    Util::set_default_filename_and_lineno($desc, debug_backtrace());
    Preview::$world->pop();
    Preview::$world->push($desc);
    return new TestAPI($desc);
}

/**
 * create a test case
 * $title is optional. if first param is a closure,
 * $title will be set to empty string
 * and $fn will be that closure.
 *
 * @param string $title
 * @param function|null $fn
 * @return object TestAPI object
 */
function test($title, $fn=null) {
    if (empty($fn) and $title instanceof \Closure) {
        $fn = $title;
        $title = "";
    }
    $case = new TestCase($title, $fn);
    if (!$fn) {
        Util::set_default_filename_and_lineno($case, debug_backtrace());
    }
    $case->set_parent(Preview::$world->current());
    return new TestAPI($case);
}

/**
 * A hook to be called before running each test case
 * in current test suite.
 *
 * @param function $fn
 * @return null
 */
function setup($fn) {
    Preview::$world->current()->add_before_each_hook($fn);
}

/**
 * A hook to be called after running each test case
 * in current test suite.
 *
 * @param function $fn
 * @return null
 */
function teardown($fn) {
    Preview::$world->current()->add_after_each_hook($fn);
}
