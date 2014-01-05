<?php

namespace Preview\Reporter;

class Spec extends Base {
    private $failed_cases = array();
    private $passed_cases = array();
    private $skipped_cases = array();

    public function after_case($case) {
        if($err = $case->error_or_failed()) {
            $this->failed_cases[] = $case;
            echo Util::color("F", "red");

        } else if($case->skipped_or_pending()) {
            $this->skipped_cases[] = $case;
            echo Util::color("*", "yellow");

        } else {
            $this->passed_cases[] = $case;
            echo Util::color(".", "green");

        }
    }

    public function after_suite($suite) {
        if ($suite->skipped()) {
            $skipped = $suite->all_cases();
            $this->skipped_cases = array_merge($this->skipped_cases, $skipped);
            echo Util::color(str_repeat("*", count($skipped)), "yellow");
        }
    }

    public function before_all($results) {
        echo Util::br();
    }

    public function after_all($results) {
        echo Util::br();
        $this->print_pending();
        $this->print_failure();
        $time = $this->timespan($results);
        $this->print_summary($time);
    }

    protected function print_pending() {
        if (empty($this->skipped_cases)) {
            return;
        }

        echo Util::br();
        echo "Pending:";
        echo Util::br();
        foreach($this->skipped_cases as $case) {
            echo Util::color("  ".$case->full_title().Util::br(), "yellow");
            $info = "    #".$case->filename().":".$case->startline().Util::br();
            echo Util::color($info, "cyan");
            echo Util::br();
        }
    }

    protected function print_failure() {
        if (empty($this->failed_cases)) {
            return;
        }

        echo Util::br();
        echo "Failures:".Util::br();
        foreach($this->failed_cases as $i =>$case) {
            $i++;
            $err = $case->error_or_failed();
            $msg = $err->getMessage();
            $trace = $this->trace_message($err->getTraceAsString());
            echo "  $i) ".$case->full_title().Util::br();
            if (!empty($msg)) {
                echo Util::color("    Failure/Error: ".$msg, "red").Util::br();
            }
            echo Util::color("    ".$trace, "cyan");
            echo Util::br();
        }
    }

    protected function print_summary($time) {
        echo Util::br();
        echo "Finished in $time seconds".Util::br();
        $failed_total = count($this->failed_cases);
        $passed_total = count($this->passed_cases);
        $skipped_total = count($this->skipped_cases);
        $total = $failed_total + $passed_total + $skipped_total;

        $message = "$total tests, $failed_total failures, ".
            "$skipped_total pending";

        if($failed_total > 0) {
            $color = "red";
        } else if ($passed_total > 0) {
            $color = "yellow";
        } else {
            $color = "green";
        }

        echo Util::color($message, $color);
        echo Util::br(2);
    }
}