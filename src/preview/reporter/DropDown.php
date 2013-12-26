<?php

namespace Preview\Reporter;

class DropDown extends Base {
    private $failed_cases = 0;
    private $passed_cases = 0;
    private $skipped_cases = 0;
    private $traces = array();

    public function after_case($case) {
        $title = $case->full_title();
        if ($case->passed()) {
            echo Util::color("  o ", "green");
            echo $title.Util::br();
            $this->passed_cases += 1;
        } else if ($case->failed()) {
            $this->failed_cases += 1;
            echo Util::color("  x ".$title.Util::br(), "red");
            $this->traces[] = array(
                $case->full_title(),
                $case->error()->getTraceAsString(),
                $case->error()->getMessage(),
            );
        } else if ($case->skipped()) {
            $this->skipped_cases += 1;
            echo Util::color("  - ".$title.Util::br(), "dark_gray");
        }
    }

    public function after_suite($suite) {
        if ($suite->skipped()) {
            $num = count($suite->all_cases());
            $this->skipped_cases += $num;
        }
    }

    public function before_all($results) {
        echo Util::br(2);
    }

    public function after_all($results) {
        $this->print_summary($this->timespan($results));

        foreach ($this->traces as $i => $t) {
            echo " ".($i + 1).") ";
            echo Util::color($t[0].Util::br(), "red");
            if (!empty($t[2])) {
                echo Util::color($t[2].Util::br(), "red");
            }
            echo $this->trace_message($t[1].Util::br(2));
        }

        echo Util::br();
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

    protected function timespan($results) {
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
        $msg_list = array_filter(explode(Util::br(), $trace));
        array_pop($msg_list);
        foreach($msg_list as $msg) {
            if (!$this->preview_file($msg)) {
                $message .= $msg.Util::br();
            }
        }
        return $message;
    }

    protected function preview_file($path) {
        $preview_dir = dirname(dirname(__DIR__));
        $preview_bin = dirname($preview_dir).DIRECTORY_SEPARATOR."preview";
        return strpos($path, $preview_dir) !== false or
            strpos($path, $preview_bin) !== false;
    }
}
