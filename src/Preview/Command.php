<?php

namespace Preview;

use Ulrichsg\Getopt;

/**
 * Command line interface
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */
class Command {

    /*
     * setup command line parser
     */
    public function __construct() {
        $this->padding = 30;
        $this->cmd = new Getopt(
            array(
                array(
                    "h", "help", Getopt::NO_ARGUMENT,
                    "Show this help message."
                ),
                array(
                    "r", "reporter", Getopt::REQUIRED_ARGUMENT,
                    "Set reporter."
                ),
                array(
                    "g", "group", Getopt::REQUIRED_ARGUMENT,
                    "Test group(s). use comma(,) to seperate groups."
                ),
                array(
                    "G", "exclude-group", Getopt::REQUIRED_ARGUMENT,
                    "Exclude group(s). use comma(,) to seperate groups."
                ),
                array(
                    "t", "title", Getopt::REQUIRED_ARGUMENT,
                    "Filter out test title regexp."
                ),
                array(
                    "c", "config", Getopt::REQUIRED_ARGUMENT,
                    "Load config file. Default './preview.config.php'."
                ),
                array(
                    "b", "backtrace", Getopt::NO_ARGUMENT,
                    "Print out full backtrace."
                ),
                array(
                    null, "order", Getopt::NO_ARGUMENT,
                    'Test are run in order.'
                ),
                array(
                    null, "no-this", Getopt::NO_ARGUMENT,
                    'Disable using $this as an implicit context(PHP 5.4).'
                ),
                array(
                    null, "no-color", Getopt::NO_ARGUMENT,
                    "Disable color output."
                ),
                array(
                    null, "with-error", Getopt::NO_ARGUMENT,
                    "Disable converting error to exception."
                ),
                array(
                    null, "fail-fast", Getopt::NO_ARGUMENT,
                    "Exit program when first failure or error occurred."
                ),
                array(
                    null, "list-groups", Getopt::NO_ARGUMENT,
                    'List all the test groups.'
                ),
                array(
                    null, "list-reporters", Getopt::NO_ARGUMENT,
                    'List available reporters.'
                )
            )
        );

        $br = PHP_EOL;
        $title = $br."    Preview ".Preview::$version.
            " - bdd test framework for php ".$br.$br;
        $this->cmd->setTitle($title);
    }


    public function run($args=null) {
        // parse options
        $br = PHP_EOL;
        $this->parse($args);
        $options = $this->cmd->getOptions();

        // setup loader and test config, test world
        Preview::$world = new World;
        Preview::$config = new Configuration;
        $loader = new Loader;

        // get parsed args, update config and load test files.
        $config_file = "preview.config.php";
        if (isset($options["config"])) {
            $config_file = $options["config"];
        }

        $config_file = realpath($config_file);
        if (is_file($config_file)) {
            Preview::$config->load_from_file($config_file);
        }

        if (isset($options["help"])) {
            $this->cmd->showHelp($this->padding);
            exit(0);
        }

        if (isset($options["list-reporters"])) {
            $reporters = array(
                "spec (defualt)",
                "dropdown",
                "tree",
                "dot",
                "line",
                "blank"
            );
            foreach ($reporters as $index => $reporter) {
                $index = $index + 1;
                echo "    $index) $reporter$br";
            }
            echo $br;
            exit(0);
        }

        if (isset($options["reporter"])) {
            $reporter_class = ucfirst($options['reporter']);
            $reporter = "\\Preview\\Reporter\\{$reporter_class}";
            Preview::$config->reporter = new $reporter;
        }

        if (isset($options["backtrace"])) {
            Preview::$config->full_backtrace = true;
        }

        if (isset($options["no-this"])) {
            Preview::$config->use_implicit_context = false;
        }

        if (isset($options["no-color"])) {
            Preview::$config->color_support = false;
        }

        if (isset($options["with-error"])) {
            Preview::$config->error_exception = false;
        }

        if (isset($options["fail-fast"])) {
            Preview::$config->fail_fast = true;
        }

        if (isset($options["order"])) {
            Preview::$config->order = true;
        }

        if (isset($options["group"])) {
            Preview::$config->test_groups = explode(",", $options["group"]);
        }

        if (isset($options["exclude-group"])) {
            Preview::$config->exclude_groups =
                explode(",", $options["exclude-group"]);
        }

        if (isset($options["title"])) {
            Preview::$config->title = $options["title"];
        }

        $files = $this->cmd->getOperands();

        // default help message
        if (empty($options) and empty($files)) {
            $this->cmd->showHelp($this->padding);
            exit(0);
        }

        // load all test file
        foreach ($files as $file) {
            $loader->load($file);
        }

        // list groups
        if (isset($options["list-groups"])) {
            $groups = Preview::$world->groups();
            if (empty($groups)) {
                echo "    No test groups.$br$br";
                exit(0);
            }
            foreach ($groups as $index => $group) {
                $index = $index + 1;
                echo "    $index) $group$br";
            }
            echo $br;
            exit(0);
        }

        $this->execute();
    }

    protected function parse($args) {
        try {
            $this->cmd->parse($args);
        } catch (\Exception $e) {
            $this->cmd->showHelp($this->padding);
            exit(1);
        }
    }

    protected function execute() {
        // run test and exit.
        $results = Preview::$world->run();
        foreach($results as $result) {
            if ($result->error_or_failed()) {
                exit(1);
            }
        }

        exit(0);
    }
}
