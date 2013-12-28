<?php

namespace Preview\DSL\BDD;
use Preview\Preview;

require_once 'ok.php';

// for php 5.3
if (Preview::php_version_is_53()) {
    describe("array_pop", function () {
        subject(array(0,1,2,3));

        it ("should return the last item", function ($self) {
            $end = end($self->subject);
            ok(array_pop($self->subject) == $end);
        });

        it ("should remove the last item from original array", function ($self) {
            array_pop($self->subject);
            ok($self->subject == array(0,1,2));
        });
    });

// for php 5.4 and above
} else {
    describe("array_pop", function () {
        subject(array(0,1,2,3));

        it ("should return the last item", function () {
            $end = end($this->subject);
            ok(array_pop($this->subject) == $end);
        });

        it ("should remove the last item from original array", function () {
            array_pop($this->subject);
            ok($this->subject == array(0,1,2));
        });
    });
}