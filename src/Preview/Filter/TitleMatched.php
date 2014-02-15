<?php

namespace Preview\Filter;

class TitleMatched extends GroupIncluded {
    public function __construct($regexp) {
        $this->regexp = $regexp;
    }

    protected function matched($test) {
        return preg_match($this->regexp, $test->title);
    }
}