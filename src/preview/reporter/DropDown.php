<?php

namespace Preview\Reporter;

class DropDown extends Base {
    protected $failed_cases = array();
    protected $passed_cases = array();
    protected $skipped_cases = array();

    public function after_case($case) {
        $title = $case->full_title();
        if ($case->passed()) {
            $this->passed_cases[] = $case;
            echo Util::color("  o ", "green").$title.Util::br();

        } else if ($case->error_or_failed()) {
            $this->failed_cases[] = $case;
            echo Util::color("  x ".$title.Util::br(), "red");

        } else {
            $this->skipped_cases[] = $case;
            echo Util::color("  - ".$title.Util::br(), "light_gray");
        }
    }

    public function after_suite($suite) {
        if ($suite->skipped()) {
            $skipped = $suite->all_cases();
            $this->skipped_cases = array_merge($this->skipped_cases, $skipped);
            foreach($skipped as $case) {
                $this->skipped_cases[] = $case;
                echo Util::color("  - ".$title.Util::br(), "light_gray");
            }
        }
    }

    public function before_all($results) {
        echo Util::br();
    }

    public function after_all($results) {
        $this->print_summary($this->timespan($results));
        echo Util::br();

        foreach ($this->failed_cases as $i => $failed) {
            echo " ".($i + 1).") ";
            echo Util::color($failed->full_title().Util::br(), "red");
            $error = $failed->error_or_failed();
            $message = $error->getMessage();
            if (!empty($message)) {
                echo Util::color($message.Util::br(), "red");
            }
            $trace = $this->trace_message($error->getTraceAsString());
            echo Util::color($trace, "light__gray").Util::br();
        }
    }

    protected function print_summary($time) {
        echo Util::br();
        echo Util::color("        passed: ", "green").count($this->passed_cases);
        echo Util::color("  failed: ", "red").count($this->failed_cases);
        echo Util::color("  skipped: ", "yellow").count($this->skipped_cases);
        echo Util::br();
        echo Util::color("        running time: ". $time. " seconds", "light_gray");
        echo Util::br(2);
    }
}
