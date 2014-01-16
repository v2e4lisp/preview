<?php

namespace Preview\Reporter;

class Line extends Dot {
    protected static $mark = "-";

    public function after_case($case) {
        if ($case->passed()) {
            $this->passed_cases[] = $case;
            echo Util::color("-", "light_gray");
        } else if ($case->error_or_failed()) {
            $this->failed_cases[] = $case;
            echo Util::color("-", "red");
        } else {
            $this->skipped_cases[] = $case;
            echo Util::color("-", "yellow");
        }
    }
}
