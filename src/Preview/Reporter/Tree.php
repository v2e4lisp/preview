<?php

namespace Preview\Reporter;

class Tree extends DropDown {
    protected $indent = 1;

    public function after_case($case) {
        $title = $case->title();
        $color = null;
        $this->indent += 1;

        if ($case->passed()) {
            $this->passed_cases[] = $case;
            $color = "green";
        } else if ($case->error_or_failed()) {
            $this->failed_cases[] = $case;
            $color = "red";
        } else {
            $this->skipped_cases[] = $case;
            $color = "yellow";
        }

        $this->indent -= 1;
        $this->print_color_string_with_current_indent("- ".$title, $color);
        echo Util::br();
    }

    public function before_suite($suite) {
        $title = $suite->title();
        $this->print_color_string_with_current_indent($title, "light_gray");
        echo Util::br();
        $this->indent += 1;
    }

    public function after_suite($suite) {
        if ($suite->skipped()) {
            $skipped = $suite->all_cases();
            $this->skipped_cases = array_merge($this->skipped_cases, $skipped);
            foreach($skipped as $case) {
                $this->skipped_cases[] = $case;
                $title = $case->title();
                $this->print_color_string_with_current_indent("- ".$title,
                                                              "yellow");
                echo Util::br();
            }
        }
        $this->indent -= 1;
    }

    public function before_all($results) {
        echo Util::br();
    }

    public function after_all($results) {
        echo Util::br();
        parent::after_all($results);
    }

    protected function print_color_string_with_current_indent($str,
                                                              $fg=null) {
        echo str_repeat("  ", $this->indent);
        echo Util::color($str, $fg);
    }
}
