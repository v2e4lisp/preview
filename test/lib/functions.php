<?php

function should_be_ok($expr) {
    if (!$expr) {
        throw new Exception;
    }
}

function should_throw($fn) {
}