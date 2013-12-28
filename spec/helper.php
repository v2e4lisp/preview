<?php

function ok($expr, $msg="") {
    if ($msg instanceof Closure) {
        $expr = $expr->__invoke();
    }

    if (!$expr) {
        throw new \Exception($msg);
    }
}

class FakeTest {
    public $result;

    public function __construct($title) {
        $this->result = new \stdClass;
        $this->result->title = $title;
        $this->result->run = false;
    }

    public function run() {
        $this->result->run = true;
    }
}
