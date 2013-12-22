<?php

namespace Preview\Reporter;

class Spec extends Base {
    private $failed_cases = 0;
    private $passed_cases = 0;
    private $skipped_cases = 0;
    private $traces = array();

    public function after_case($case) {
        if($case->failed()) {
            $this->failed_cases += 1;
            $this->traces[] = array(
                $case->full_title(),
                $case->error()->getTraceAsString(),
            );
            echo Util::color(". ", "red");
        } else if($case->passed()) {
            $this->passed_cases += 1;
            echo Util::color(". ", "green");
        } else if($case->skipped()) {
            $this->skipped_cases += 1;
            echo Util::color(". ", "yellow");
        }
    }

    public function after_suite($suite) {
        if ($suite->skipped()) {
            $num = count($suite->all_cases());
            echo Util::color(str_repeat(". ", $num), "yellow");
            $this->skipped_cases += $num;
        }
    }

    public function before_all($results) {
        echo Util::br(2)."    ";
    }

    public function after_all($results) {
        $this->print_summary($this->timespan($results));

        foreach ($this->traces as $i => $t) {
            echo " ".($i + 1).") ";
            echo Util::color($t[0].Util::br(), "red");
            echo $t[1].Util::br(2);
            // echo $this->trace_message($t[1]).Util::br();
        }
    }

    protected function print_summary($time) {
        echo Util::br(2);
        echo Util::color("        passed: ", "green");
        echo $this->passed_cases;
        echo Util::color("  failed: ", "red");
        echo $this->failed_cases;
        echo Util::color("  skipped: ", "yellow");
        echo $this->skipped_cases;
        echo Util::br();
        echo Util::color("        running time: ". $time. " seconds", "dark_gray");
        echo Util::br(2);
    }

    public function timespan($results) {
        $time = 0;
        foreach($results as $result) {
            $time += $result->time();
        }
        return $time;
    }

    /**
     * format trace message
     */
    protected function trace_message($trace) {
        $message = "";
        $msg_list = explode(Util::br(), $trace);
        foreach($msg_list as $msg) {
            if (!$this->from_mocha_file($msg)) {
                $message .= $msg.Util::br();
            }
        }
        return $message;
    }

    protected function from_mocha_file($path) {
        $mocha_dir = dirname(dirname(__DIR__));
        return strpos($path, $mocha_dir) !== false;
    }
}
