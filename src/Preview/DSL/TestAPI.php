<?php
/**
 * TestAPI class for end user.
 * End user use this class to call method on test object. Since test case/suite
 * object has so many public functions we don't want our users to use.
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL;

class TestAPI {
    /**
     * The actual test object all methods will be delegated to.
     *
     * @var TestSuite/TestCase $test
     */
    protected $test;
    
    public function __construct($test) {
        $this->test = $test;
    }

    /**
     * Skip test case/suite.
     *
     * @param null
     * @return $this
     */
    public function skip() {
        $this->test->skip();
        return $this;
    }

    /**
     * Group test.
     *
     * @param mixed group names (one or more strings)
     * @return $this
     */
    public function group() {
        $groups = func_get_args();
        foreach ($groups as $group) {
            $this->test->add_to_group($group);
        }

        return $this;
    }
}
