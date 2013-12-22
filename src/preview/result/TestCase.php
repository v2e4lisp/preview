<?php
/**
 * Test case result class
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\Result;

class TestCase extends TestBase {
    public function titles() {
        $titles = array($this->title());
        $suite = $this->parent();
        while($suite) {
            $titles[] = $suite->title();
            $suite = $suite->parent();
        }

        return array_reverse($titles);
    }

    public function full_title() {
        return implode($this->titles(), " ");
    }
}