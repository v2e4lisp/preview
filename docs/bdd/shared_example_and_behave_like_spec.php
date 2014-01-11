<?php
namespace Preview\DSL\BDD;

require_once __DIR__.'/../ok.php';

shared_example("to share test", function () {
    it("will use the caller's context", function () {
        ok($this->name == "wenjun.yan");
    });

    describe("create a test suite here", function () {
        it("and still have access to vars defined caller", function () {
            ok($this->name == "wenjun.yan");
        });
    });
});

describe("it_behaves_like", function () {
    before_each(function () {
        $this->name = "wenjun.yan";
    });

    /*
     * the following line will be replaced by
     * code defined in shared_exmaple "to share test";
     */
    it_behaves_like("to share test");
});