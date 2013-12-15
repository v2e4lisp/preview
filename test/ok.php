<?php

function ok($expr, $msg="") {
    if (!$expr) {
        throw new \Exception($msg);
    }
}