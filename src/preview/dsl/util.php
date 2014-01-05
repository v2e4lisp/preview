<?php

namespace Preview\DSL;

class Util {
    public static function set_default_filename_and_lineno($test, $trace) {
        foreach($trace as $t) {
            if (strpos($t["file"], __DIR__) === false) {
                $test->filename = $trace[0]["file"];
                $test->startline = $trace[0]["line"];
                $test->endline = $trace[0]["line"];
                break;
            }
        }
    }
}
