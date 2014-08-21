<?php

namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\Configuration;
use Preview\Core\TestCase;
use Preview\Core\TestSuite;
use Preview\Reporter\Base as BaseReporter;

describe("TestCase", function () {

    before_each(function () {
        $this->suite = new TestSuite("sample test suite", function () {});
        $this->test = new TestCase("sample test case", function () {});
        $this->test->set_parent($this->suite);
    });

    describe("#set_parent", function () {
        it("set parent test suite", function () {
            ok($this->suite->cases == array($this->test));
            ok($this->suite == $this->test->parent);
        });
    });

    describe("#add_to_group", function () {
        it("add itself to group", function () {
            $this->test->add_to_group("sample group");
            ok($this->test->in_group("sample group"));
        });
    });

    describe("#force_error", function () {
        before_each(function () {
            $this->test->force_error(new \ErrorException("error"));
            $this->subject = $this->test;
        });

        it_behaves_like("error test");
    });

    describe("#set_error", function () {
        before_each(function () {
            $this->test->set_error(new \ErrorException("error"));
        });

        it("set error for itself", function () {
            ok($this->test->error);
        });

        it("set error for parent", function () {
            ok($this->suite->error);
        });

        context("when set error again", function () {
            before_each(function () {
                $this->test->set_error(new \ErrorException("new error"));
            });

            it("should hold the first error", function () {
                ok($this->test->error->getMessage() == "error");
            });

            it("parent suite should hold the first error", function () {
                ok($this->suite->error->getMessage() == "error");
            });
        });
    });

    describe("#run", function () {
        before(function () {
            $this->old_config = Preview::$config;
        });

        before_each(function () {
            Preview::$config = new Configuration;
            Preview::$config->reporter = new BaseReporter;
        });

        context("when passed", function () {
            before_each(function () {
                $this->subject = $this->test;
                $this->subject->run();
                Preview::$config = $this->old_config;
            });

            it_behaves_like("finished test");
        });

        context("when failed", function () {
            context("in test body", function () {
                before_each (function () {
                    $this->subject = new TestCase("sample case", function () {
                        ok(false);
                    });
                });

                before_each(function () {
                    $this->subject->set_parent($this->suite);
                    $this->subject->run();
                    Preview::$config = $this->old_config;
                });

                it_behaves_like("failed test");
            });

            context("in before_each", function () {
                before_each(function () {
                    $this->subject = new TestCase("sample case", function () {});
                    $this->subject->set_parent($this->suite);
                    $this->suite->add_before_each_hook(function () {
                        ok(false);
                    });
                    $this->subject->run();
                    Preview::$config = $this->old_config;
                });

                it_behaves_like("failed test");
            });

            context("in after_each", function () {
                before_each (function () {
                    $this->subject = new TestCase("sample case", function () {});
                });

                it("should throw the exception", function () {

                    $this->subject->set_parent($this->suite);
                    $this->suite->add_after_each_hook(function () {
                        ok(false);
                    });

                    $exception_thrown = false;
                    try {
                        $this->subject->run();
                    } catch (\Exception $e) {
                        $exception_thrown = true;
                    }
                    Preview::$config = $this->old_config;

                    ok($exception_thrown);

                });
            });
        });

        context("when error occured", function () {
            context("in before", function () {
                before_each (function () {
                    $this->subject = new TestCase("sample case", function () {});
                });

                before_each(function () {
                    $this->subject->set_parent($this->suite);
                    $this->suite->add_before_hook(function () {
                        ok(false);
                    });
                    $this->suite->run();
                    Preview::$config = $this->old_config;
                });

                it_behaves_like("error test");
            });

            context("in before_each", function () {
                before_each (function () {
                    $this->subject = new TestCase("sample case", function () {});
                });

                before_each(function () {
                    $this->subject->set_parent($this->suite);
                    $this->suite->add_before_each_hook(function () {
                        $a->user;
                    });
                    $this->subject->run();
                    Preview::$config = $this->old_config;
                });

                it_behaves_like("error test");
            });

            context("in after_each", function () {
                before_each (function () {
                    $this->subject = new TestCase("sample case", function () {});
                });

                it("should throw the exception", function () {
                    $this->subject->set_parent($this->suite);
                    $this->suite->add_after_each_hook(function () {
                        $a->user;
                    });

                    $exception_thrown = false;
                    try {
                        $this->subject->run();
                    } catch (\Exception $e) {
                        $exception_thrown = true;
                    }
                    Preview::$config = $this->old_config;
                    ok($exception_thrown);
                });
            });

            context("in after", function () {
                before_each (function () {
                    $this->subject = new TestCase("sample case", function () {});
                });

                it("should throw the exception", function () {
                    $this->subject->set_parent($this->suite);
                    $this->suite->add_after_hook(function () {
                        $a->user;
                    });

                    $exception_thrown = false;
                    try {
                        $this->suite->run();
                    } catch (\Exception $e) {
                        $exception_thrown = true;
                    }
                    Preview::$config = $this->old_config;
                    ok($exception_thrown);
                });
            });
        });
    });
});
