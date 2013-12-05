<?php

namespace Preview;

require_once 'base.php';

class DefaultReporter extends ReporterBase {
    private $error_cases = 0;
    private $traces = array();
    private $title_list = array();
    private $cases = 0;

    public function after_case($case) {
        $this->cases += 1;

        if($case->error()) {
            $this->error_cases += 1;
            $this->traces[] = array(
                implode($this->title_list, " ")." ".$case->title(),
                $case->error()->getTraceAsString(),
            );
            cecho(".", "red");
        } else {
            cecho(".", "green");
        }
    }

    public function before_suite($suite) {
        $this->title_list[] = $suite->title();
    }

    public function after_suite($suite) {
        array_pop($this->title_list);
    }

    public function before_all($results) {
        echo "\n\n    ";
    }

    public function after_all($results) {
        $this->print_summary();

        foreach ($this->traces as $t) {
            cecho("  ".$t[0]."\n", "red");
            echo $t[1]."\n\n";
            // echo $this->trace_message($t[1])."\n";
        }
    }

    protected function print_summary() {
        echo "\n\n";
        cecho("        passed: ", "green");
        echo ($this->cases - $this->error_cases);
        cecho("  failed: ", "red");
        echo $this->error_cases;
        echo "\n\n";
    }

    /**
     * format trace message
     */
    protected function trace_message($trace) {
        $message = "";
        $msg_list = explode("\n", $trace);
        foreach($msg_list as $msg) {
            if (!$this->from_mocha_file($msg)) {
                $message .= $msg."\n";
            }
        }
        return $message;
    }

    protected function from_mocha_file($path) {
        $mocha_dir = dirname(dirname(__DIR__));
        return strpos($path, $mocha_dir) !== false;
    }
}
