<?php
/**
 * Filter Base
 * Filter out tests before run them.
 *
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 * @package Preview
 */

namespace Preview\Filter;

class Base {

    /**
     * The only api for filter.
     *
     * @param array $suites an array of TestSuite object
     * @retrun arrray an array of TestSuite object
     */
    public function run($suites) {
        return $suites;
    }
}