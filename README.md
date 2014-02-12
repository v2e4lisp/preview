# Preview
[![Stories in Ready](https://badge.waffle.io/v2e4lisp/preview.png?label=ready)](http://waffle.io/v2e4lisp/preview)
[![Build Status](https://travis-ci.org/v2e4lisp/preview.png?branch=master)](https://travis-ci.org/v2e4lisp/preview)

BDD test for php.

Heavily inspired by [mocha.js](http://visionmedia.github.io/mocha/)
and [Rspec](https://github.com/rspec)

## [Document](./docs)

## Sample Code
### BDD rspec-like syntax
```php
<?php
namespace Preview\DSL\BDD;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

describe("Stack", function () {
    before_each(function () {
        $this->stack = new \Stack(array(1,2,3));
    });

    describe("#size", function () {
        it("returns the size of stack", function () {
            ok($this->stack->size() == 3);
        });
    });

    describe("#peek", function () {
        it("returns the last element", function () {
            ok($this->stack->peek() == 3);
        });
    });

    describe("#push", function () {
        it("pushes an element to stack", function () {
            $this->stack->push(4);
            ok($this->stack->peek() == 4);
            ok($this->stack->size() == 4);
        });
    });

    describe("#pop", function () {
        it("pops out the last element", function () {
            ok($this->stack->pop() == 3);
            ok($this->stack->size() == 2);
        });
    });
});
```

### TDD is aliases for bdd
```php
<?php
namespace Preview\DSL\TDD;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

suite("Stack", function () {
    setup(function () {
        $this->stack = new \Stack(array(1,2,3));
    });

    suite("#size", function () {
        test("returns the size of stack", function () {
            ok($this->stack->size() == 3);
        });
    });

    suite("#peek", function () {
        test("returns the last element", function () {
            ok($this->stack->peek() == 3);
        });
    });

    suite("#push", function () {
        test("pushes an element to stack", function () {
            $this->stack->push(4);
            ok($this->stack->peek() == 4);
            ok($this->stack->size() == 4);
        });
    });

    suite("#pop", function () {
        test("pops out the last element", function () {
            ok($this->stack->pop() == 3);
            ok($this->stack->size() == 2);
        });
    });
});
```

### Qunit for simple test
```php
<?php
namespace Preview\DSL\Qunit;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

suite("Stack");

setup(function () {
    $this->stack = new \Stack(array(1,2,3));
});

test("#size returns the size of stack", function () {
    ok($this->stack->size() == 3);
});

test("#peek eturns the last element", function () {
    ok($this->stack->peek() == 3);
});

test("#push pushes an element to stack", function () {
    $this->stack->push(4);
    ok($this->stack->peek() == 4);
    ok($this->stack->size() == 4);
});

test("#pop pops out the last element", function () {
    ok($this->stack->pop() == 3);
    ok($this->stack->size() == 2);
});
```

### Export an array of tests.
```php
<?php
namespace Preview\DSL\Export;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

$suite = array(
    "before each" => function () {
        $this->stack = new \Stack(array(1,2,3));
    },

    "#sizereturns the size of stack" => function () {
        ok($this->stack->size() == 3);
    },

    "#peek eturns the last element" => function () {
        ok($this->stack->peek() == 3);
    },

    "#push pushes an element to stack" => function () {
        $this->stack->push(4);
        ok($this->stack->peek() == 4);
        ok($this->stack->size() == 4);
    },

    "#pop pops out the last element" => function () {
        ok($this->stack->pop() == 3);
        ok($this->stack->size() == 2);
    }
);

export("Stack", $suite);
```

### Testify syntax from [this repo](https://github.com/marco-fiset/Testify.php)
```php
<?php
namespace Preview\DSL\Testify;

require_once 'stack.php';
require_once __DIR__.'/../ok.php';

$suite = new Suite("Stack[testify]");

$suite->before_each(function () {
    $this->stack = new \Stack(array(1,2,3));

})->test("#size returns the size of stack", function () {
    ok($this->stack->size() == 3);

})->test("#peek eturns the last element", function () {
    ok($this->stack->peek() == 3);

})->test("#push pushes an element to stack", function () {
    $this->stack->push(4);
    ok($this->stack->peek() == 4);
    ok($this->stack->size() == 4);

})->test("#pop pops out the last element", function () {
    ok($this->stack->pop() == 3);
    ok($this->stack->size() == 2);

})->load();
```

## Contributors
* Yan Wenjun(@v2e4lisp)
* Noritaka Horio(@holyshared)
