<?php

function ok($expr, $msg="") {
    if ($msg instanceof Closure) {
        $expr = $expr->__invoke();
    }

    if (!$expr) {
        throw new \Exception($msg);
    }
}


class Recorder {
    public $before_all = 0;
    public $after_all = 0;
    public $before_suite = 0;
    public $after_suite = 0;
    public $before_case = 0;
    public $after_case = 0;

    public function before_all($results) {
        $this->before_all++;
    }

    public function after_all($results) {
        $this->after_all++;
    }

    public function before_suite($suite) {
        $this->before_suite++;
    }

    public function after_suite($suite) {
        $this->after_suite++;
    }

    public function before_case($case) {
        $this->before_case++;
    }

    public function after_case($case) {
        $this->after_case++;
    }
}