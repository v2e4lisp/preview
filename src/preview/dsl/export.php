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

function export ($title, $options) {
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
