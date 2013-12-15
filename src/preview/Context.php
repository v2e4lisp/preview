<?php

namespace Preview;

class Context {
    public function __set($name, $value) {
        $this->$name = $value;
    }
}
