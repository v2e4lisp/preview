<?php
/**
 * GroupExcluded Filter
 * Filter out test belonging to some groups
 * The filter logic is almost the same as the GroupIncluded filter.
 *
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 * @package Preview
 */

namespace Preview\Filter;

class GroupExcluded extends GroupIncluded {

    /**
     * Check if the test object should be filtered out.
     *
     * @param TestSuite|TestCase $test
     * @return bool
     */
    protected function matched($test) {
        foreach ($this->groups as $group) {
            if ($test->in_group($group)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Filter a TestSuite
     * 1. decide if this TestSuite itself should be removed
     *    TestSuite should be removed if it's not matched OR
     *    any of its child test is not matched.
     * 2. recursively filter out its children
     *
     * If TestSuite is not matched then done, remove the whole test suite.
     *
     * @param TestSuite $suite
     * @return bool
     */
    protected function filter($suite) {
        if (!$this->matched($suite)) {
            return false;
        }

        return $this->filter_children($suite);
    }
}
