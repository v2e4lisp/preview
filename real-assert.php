<?php

class AssertionException extends Exception {}

function realAssert($stmt, $message="failed.") {
    if(!$stmt) {
        throw new AssertionException($message);
    }
}