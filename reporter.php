<?php
require_once 'color.php';
require_once 'reporter_base.php';

class DefaultReporter extends ReporterBase {
    public function after_case($case) {
        if($case->error()) {
            cli(".", "red");
        } else {
            cli(".", "green");
        }
    }

    public function after_all($results) {
        echo "\n";
    }
}
