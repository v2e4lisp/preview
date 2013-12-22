<?php
/**
 * Testify style dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\Testify;

use \Preview\World;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;
use \Preview\DSL\TestAPI;

class Suite {
    private $suite;

    public function __construct($title) {
        $suite = new TestSuite($title, function(){});
        World::pop();
        World::push($suite);
        $this->suite = $suite;
    }

    public function skip() {
        $this->suite->skip();
        return $this;
    }

    public function group() {
        $groups = func_get_args();
        foreach($groups as $group) {
            $this->suite->group($group);
        }
        return $this;
    }

    public function before($fn) {
        $this->suite->before($fn);
        return $this;
    }

    public function after($fn) {
        $this->suite->after($fn);
        return $this;
    }

    public function before_each($fn) {
        $this->suite->before_each($fn);
        return $this;
    }

    public function after_each($fn) {
        $this->suite->after_each($fn);
        return $this;
    }

    public function test($title, $fn=null) {
        if (empty($fn) and $title instanceof \Closure) {
            $fn = $title;
            $title = "";
        }
        $case = new TestCase($title, $fn);
        $this->suite->add($case);
        return new TestAPI($case);
    }
}