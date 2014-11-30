<?php
/**
 * GroupIncluded Filter
 * Filter out test objects that do not belong to certain groups
 *
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 * @package Preview
 */

namespace Preview\Filter;

class GroupIncluded extends Base {
    /**
     * constructor
     *
     * @param array $groups an array of test group(string)
     */
    public function __construct($groups) {
        $this->groups = $groups;
    }

    /**
     * Filter out test objects
     * that do not belong to any of the $this->groups
     *
     * @param array $suites an array of TestSuite object
     * @return arrray an array of TestSuite object
     */
    public function run($suites) {
        $filtered = array();
        foreach ($suites as $suite) {
            if ($this->filter($suite)) {
                $filtered[] = $suite;
            }
        }

        return $filtered;
    }

    /**
     * Check if the test object should be filtered out.
     *
     * @param TestSuite|TestCase $test
     * @return bool
     */
    protected function matched($test) {
        foreach ($this->groups as $group) {
            if ($test->in_group($group)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Filter a TestSuite
     * 1. decide if this TestSuite itself should be removed
     *    TestSuite should be removed only if it's not matched
     *    nor any of its children
     * 2. recursively filter out its children
     *
     * If TestSuite is matched then done, no further filtering.
     *
     * @param TestSuite $suite
     * @return bool
     */
    protected function filter($suite) {
        if ($this->matched($suite)) {
            return true;
        }

        return $this->filter_children($suite);
    }

    /**
     * Filter out a TestSuite's children tests
     *
     * @param TestSuite $suite
     * @return bool any of child test object matched
     */
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
