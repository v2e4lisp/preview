<?php

namespace Preview\Reporter;

class Dot extends DropDown {
    protected static $mark = ". ";

    public function after_case($case) {
        $title = $case->full_title();
        if ($case->passed()) {
            $this->passed_cases[] = $case;
            echo Util::color(static::$mark, "green");
        } else if ($case->error_or_failed()) {
            $this->failed_cases[] = $case;
            echo Util::color(static::$mark, "red");
        } else {
            $this->skipped_cases[] = $case;
            echo Util::color(static::$mark, "yellow");
        }
    }

    public function after_suite($suite) {
        if ($suite->skipped()) {
            $skipped = $suite->all_cases();
            $this->skipped_cases = array_merge($this->skipped_cases, $skipped);
            foreach($skipped as $case) {
                $this->skipped_cases[] = $case;
                echo Util::color(static::$mark, "yellow");
            }
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
