<?php
// require_once 'color.php';
require_once 'base.php';
require_once './cecho.php';

class DefaultReporter extends ReporterBase {
    private $error_cases = array();
    private $cases = array();

    public function after_case($case) {
        $this->cases[] = $case;
        if($case->error()) {
            $this->error_cases[] = $case;
            cecho(".", "red");
        } else {
            cecho(".", "green");
        }
    }

    public function after_all($results) {
        cecho();
        foreach($this->error_cases as $case) {
            $titles = array($case->title());
            $org = $case;
            while($case->parent()) {
                $case = $case->parent();
                $titles[] = $case->title();
            }

            $titles = array_reverse($titles);

            foreach($titles as $indent => $title) {
                cecho(str_repeat("    ", $indent));
                cecho($title, 'dark_gray');
                cecho();
            }
            cecho($org->error()->getMessage(), 'red');
            cecho();
            cecho($org->error()->getTraceAsString());
        }
    }
}
