<?php
require_once './dsl.php';
require_once './real_assert.php';
require_once 'reporter/default.php';

Configuration::set_reporter(DefaultReporter);
Configuration::set_assertion_errors(AssertionException);

describe("Array", function () {
    $sample = array(1,2,3);

    beforeEach(function () use (&$sample) {
        $sample = array(1,2,3);
    });

    describe("array_pop", function () use ($sample) {
        it("should remove the last item.", function () use ($sample) {
            realAssert(array_pop($sample) == 3);
            realAssert($sample == array(1,2));
        });

    });

    describe("#array_push", function () use ($sample) {
        it("should append new item to array", function () use ($sample) {
            array_push($sample, 4);
            realAssert($sample == array(1,2,2,4));
        });
    });
});

World::run();
