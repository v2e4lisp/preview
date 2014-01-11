<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../../ok.php';

describe("subject", function () {
    subject("wenjun.yan");

    it("shorthand for let('subject', something)", function () {
        ok($this->subject == "wenjun.yan");
    });
});