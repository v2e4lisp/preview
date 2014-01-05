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

function export ($title, $options) {
    $suite = new TestSuite($title, function() {});
    $suite->set_parent(Preview::$world->current());
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
    if (isset($options["tests"])) {
        $tests = $options["tests"];
        foreach($tests as $key => $fn) {
            $suite->add(new TestCase($key, $fn));
        }
    }

    // export suites
    $keywords = $hook_names;
    $keywords[] = "tests";
    foreach ($options as $key => $opt) {
        if (!in_array($key, $keywords)) {
            export($key, $opt);
        }
    }

    Preview::$world->pop();
}
