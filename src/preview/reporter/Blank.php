<?php

namespace Preview\Reporter;

class Blank extends Dot {
    protected static $mark = " ";
    protected $row = 0;
    protected $total_cases = 0;

    public function after_case($case) {
        if ($this->should_goto_next_row()) {
            $this->row += 1;
            echo Util::br()."    ";
        }

        $this->total_cases += 1;

        if ($case->passed()) {
            $this->passed_cases[] = $case;
            echo Util::color(" ", null, "green");
        } else if ($case->error_or_failed()) {
            $this->failed_cases[] = $case;
            echo Util::color(" ", null, "red");
        } else {
            $this->skipped_cases[] = $case;
            echo Util::color(" ", null, "yellow");
        }
    }

    public function after_suite($suite) {
        if ($suite->skipped()) {
            $skipped = $suite->all_cases();
            $this->skipped_cases = array_merge($this->skipped_cases, $skipped);
            foreach($skipped as $case) {
                $this->skipped_cases[] = $case;
                echo Util::color(static::$mark, null, "yellow");
            }
        }
    }

    protected function should_goto_next_row() {
        return floor($this->total_cases / 60) > $this->row;
    }
}
