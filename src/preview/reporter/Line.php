<?php

namespace Preview\Reporter;

class Line extends Dot {
    public function after_case($case) {
        $title = $case->full_title();
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
