<?php
/**
 * array structured test dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\Export;

use \Preview\Preview;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;
use \Preview\DSL\Util;

/**
 * Load an array of tests (a test suite)
 *
 * @param string|array $title, title of a test suite or an array of tests
 * @param array|null $options, an array of tests
 *        key for hooks: "before", "after", "before each", "after each",
 *        other keys are for test cases.
 */
function export ($title, $options=null) {
    if (is_null($options) and is_array($title)) {
        $options = $title;
        $title = "";
    }

    $suite = new TestSuite($title, function() {});
    Util::set_default_filename_and_lineno($suite, debug_backtrace());
    Preview::$world->pop();
    Preview::$world->push($suite);

    // add hooks
    $hook_names = array("before", "after", "before each", "after each");
    foreach($hook_names as $hook_name) {
        if(isset($options[$hook_name])) {
            $hooks = $options[$hook_name];
            if (!is_array($options[$hook_name])) {
                $hooks = array($hooks);
            }

            $add_hook = "add_".str_replace(" ", "_", $hook_name)."_hook";
            foreach($hooks as $hook) {
                $suite->$add_hook($hook);
            }
        }
    }

    // add tests
    foreach ($options as $title => $fn) {
        if (!in_array($title, $hook_names)) {
            $suite->add(new TestCase($title, $fn));
        }
    }
}
