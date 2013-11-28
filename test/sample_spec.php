<?php
namespace Mocha\BDD;

require "lib/functions.php";

describe("Array functions", function() {
    it("array_pop", function () {
        $user = "wenjun.yan";
        ithrow($user);
    });

    describe("pending suite one");

    describe("pending suite two");

    it("array_push", function () {
        $sample = array(1,2,3);
        array_push($sample, 4);
        return $sample == array(1,2,2,3,4);
    });
});
