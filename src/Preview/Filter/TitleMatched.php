<?php
/**
 * TitleMatched Filter
 * Filter out all tests whose title does not match a regexp.
 * The filter logic is the same as the GroupIncluded Filter.
 *
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 * @package Preview
 */

namespace Preview\Filter;

class TitleMatched extends GroupIncluded {
    /**
     * constructor
     *
     * @param regexp $regexp
     */
    public function __construct($regexp) {
        $this->regexp = $regexp;
    }

    /**
     * Check if the test object should be filtered out.
     *
     * @param string $param
     * @return null
     */
    protected function matched($test) {
        return preg_match($this->regexp, $test->title);
    }
}
