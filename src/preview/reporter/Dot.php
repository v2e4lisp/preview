<?php

namespace Preview\Reporter;

class Dot extends DropDown {
    public function after_case($case) {
        $title = $case->full_title();
        if ($case->passed()) {
            $this->passed_cases[] = $case;
            echo Util::color(". ", "green");
        } else if ($case->error_or_failed()) {
            $this->failed_cases[] = $case;
            echo Util::color(". ", "red");
        } else {
            $this->skipped_cases[] = $case;
            echo Util::color(". ", "yellow");
        }
    }

    public function before_all($results) {
        echo Util::br()."    ";
    }

    public function after_all($results) {
        echo Util::br();
        parent::after_all($results);
    }
}
