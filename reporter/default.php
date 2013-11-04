<?php
// require_once 'color.php';
require_once 'reporter_base.php';

class DefaultReporter extends ReporterBase {
    public function after_case($case) {
        if($case->error()) {
            cecho(".", "red");
        } else {
            cecho(".", "green");
        }
    }

    public function after_all($results) {
        cecho();
    }
}
