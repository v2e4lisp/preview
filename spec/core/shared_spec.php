<?php

namespace Preview\DSL\BDD;

use Preview\Core\TestShared;

describe("TestShared", function () {
    subject(new TestShared("foo", function ($message, $obj) {
        $obj->message = $message;
    }));

    describe("#name", function () {
        it ("should return the name", function () {
            ok($this->subject->name() == "foo");
        });
    });

    describe("#setup", function () {
        it("body should be invoked with args", function () {
            $obj = new \stdClass;
            $this->subject->setup(array("bar", $obj));
            ok($obj->message == "bar");
        });
    });
});