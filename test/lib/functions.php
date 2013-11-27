<?php

function ithrow() {
    $back = debug_backtrace();
    $message = '';
    foreach ($back as $t) {
        $message .= "\n{$t['file']}: line {$t['line']}";
    }
    $message = "random chat";
    throw new Exception($message);
}