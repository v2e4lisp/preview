<?php

namespace Preview\Filter;

class Randomized extends Base {
    public function run($suites) {
        shuffle($suites);
        foreach ($suites as $suite) {
            $this->randomize($suite);
        }

        return $suites;
    }

    protected function randomize($suite) {
        shuffle($suite->cases);
        shuffle($suite->suites);

        foreach ($suite->suites as $child_suite) {
            $this->randomize($child_suite);
        }
    }
}