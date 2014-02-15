<?php

namespace Preview\Filter;

class GroupIncluded extends Base {
    public function __construct($groups) {
        $this->groups = $groups;
    }

    public function run($suites) {
        $filtered = array();
        foreach ($suites as $suite) {
            if ($this->filter($suite)) {
                $filtered[] = $suite;
            }
        }

        return $filtered;
    }

    protected function matched($test) {
        foreach ($this->groups as $group) {
            if ($test->in_group($group)) {
                return true;
            }
        }
        return false;
    }

    protected function filter($suite) {
        if ($this->matched($suite)) {
            return true;
        }

        return $this->filter_children($suite);
    }

    protected function filter_children($suite) {
        $any_child_matched = false;

        // testcase children
        foreach ($suite->cases as $case) {
            if ($this->matched($case)) {
                $any_child_matched = true;
            } else {
                $suite->remove($case);
            }
        }

        // testsuite children
        foreach ($suite->suites as $child_suite) {
            if ($this->filter($child_suite)) {
                $any_child_matched = true;
            } else {
                $suite->remove($child_suite);
            }
        }

        return $any_child_matched;
    }
}