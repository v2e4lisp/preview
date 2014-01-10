<?php

class Stack {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function push($elem) {
        $this->data[] = $elem;
    }

    public function pop() {
        return array_pop($this->data);
    }

    public function peek() {
        return end($this->data);
    }

    public function size() {
        return count($this->data);
    }
}