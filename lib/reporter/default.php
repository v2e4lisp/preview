<?php

namespace Mocha;

require_once 'base.php';

class DefaultReporter extends ReporterBase {
    private $error_cases = 0;
    private $traces = array();
    private $cases = 0;
    private $level = 0;
    private $indent = "  ";

    public function after_case($case) {
        $this->level += 1;
        if($case->error()) {
            $this->error_cases += 1;
            $this->cases += 1;
            cecho();
            $this->print_indent();
            cecho($case->title(), "red");
            $this->traces[] = $case->error()->getTraceAsString();
        } else {
            $this->cases += 1;
            cecho();
            $this->print_indent();
            cecho($case->title(), "green");
        }
        $this->level -= 1;
    }

    public function before_suite($suite) {
        $this->level += 1;
        cecho();
        $this->print_indent();
        cecho($suite->title(), "dark_gray");
    }

    public function after_suite($suite) {
        $this->level -= 1;
    }

    public function after_all($results) {
        echo "\n\n";
        foreach ($this->traces as $t) {
            cecho($t."\n\n", "dark_gray");
        }
        echo "\n\n\n";
        cecho("        passed: ", "green");
        echo ($this->cases - $this->error_cases);
        cecho("  failed: ", "red");
        echo $this->error_cases;
        echo "\n\n";
    }

    private function print_indent() {
        echo str_repeat($this->indent, $this->level);
    }
}
