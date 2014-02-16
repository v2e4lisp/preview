<?php
/**
 * default settings
 */

namespace Preview;

return array(
    /*
     * set reporter
     * cli option:
     *     -r [reporter name]
     */
    "reporter" => "\\Preview\\Reporter\\Spec",

    /*
     * exceptions to catch as failure.
     */
    "assertion_exceptions" => array("\\Exception"),

    /*
     * convert error to ErrorException.
     * cli option:
     *     --with-error disable convertion
     */
    "convert_error_to_exception" => true,

    /*
     * exit when first failure or error occured.
     * cli option:
     *     --fail-fast
     */
    "fail_fast" => true,

    /*
     * output with color
     * set to true if term is tty.
     * cli option:
     *     --no-color Disable color ouput
     */
    "color_support" => Preview::is_tty(),

    /*
     * print out full backtrace
     * cli option:
     *     -b, --backtrace
     */
    "full_backtrace" => false,

    /*
     * run test groups
     * cli option:
     *    -g, --group [test groups]
     */
    "test_groups" => array(),

    /*
     * filter out tests in groups
     * cli option:
     *   -G, --exclude-group [test groups]
     */
    "exclude_groups" => array(),

    /*
     * only run tests whose title match the title regexp
     * cli option:
     *  -t, --title [title regexp]
     */
    "title" => null,

    /*
     * run tests in order
     * cli option:
     *     --order set to true
     */
    "order" => false,

    /*
     * custom filters run before executing tests
     * Option 'title', 'order', "test_groups", "exclude_groups"
     * are built in filters
     */
    "custom_filters" => array(),

    /*
     * Use $this as an implict context.
     * false if php is 5.3
     *
     * cli option:
     *     --no-this disable implicit context, only for PHP 5.4 an above.
     */
    "use_implicit_context" => !Preview::is_php53(),

    /*
     * regexp to match spec filename
     * files do not match this regexp won't be loaded.
     */
    "spec_file_regexp" => '/_spec\.php/',

    /*
     * shared dir will be first loaded.
     */
    "shared_dir_name" => "shared",

    /*
     * global before hook
     */
    "before_hook" => null,

    /*
     * global after hook
     */
    "after_hook" => null,

    /*
     * global before each hook
     */
    "before_each_hook" => null,

    /*
     * global after each hook
     */
    "after_each_hook" => null,
);
