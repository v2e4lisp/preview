<?php

namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\Core\TestCase;
use Preview\Core\TestSuite;
use Preview\Reporter\Base as BaseReporter;

describe("TestCase", function () {
    subject(function () {
        return new TestCase("test case", function () {});
    });

    it_behaves_like("groupable test");

    context("when skipped", function () {
        before_each(function () {
            $this->subject->skip();
        });

        it_behaves_like("skipped test");
    });

    context("when pending", function () {
        subject(new TestCase("test case", null));
        it_behaves_like("pending test");
    });

    context("when finished", function () {
        before_each(function () {
            $this->subject->set_parent(new TestSuite("test suite",
                                                     function () {}));

            $old_reporter = Preview::$config->reporter;
            Preview::$config->reporter = new BaseReporter;
            $this->subject->run();
            Preview::$config->reporter = $old_reporter;
        });

        it_behaves_like("finished test");
    });
});
