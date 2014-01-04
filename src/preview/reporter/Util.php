<?php

namespace Preview\Reporter;

/**
 * color output orignal code:
 *
 * http://www.if-not-true-then-false.com/2010/
 * php-class-for-coloring-php-command-line-cli-scripts-output
 * -php-output-colorizing-using-bash-shell-colors/
 *
 */

use Preview\Preview;

class Util {
    private static $foreground_colors = array(
        'black'        => '0;30',
        'dark_gray'    => '1;30',
        'blue'         => '0;34',
        'light_blue'   => '1;34',
        'green'        => '0;32',
        'light_green'  => '1;32',
        'cyan'         => '0;36',
        'light_cyan'   => '1;36',
        'red'          => '0;31',
        'light_red'    => '1;31',
        'purple'       => '0;35',
        'light_purple' => '1;35',
        'brown'        => '0;33',
        'yellow'       => '1;33',
        'light_gray'   => '0;37',
        'white'        => '1;37',
    );

    private static $background_colors = array(
        'black'        => '40',
        'red'          => '41',
        'green'        => '42',
        'yellow'       => '43',
        'blue'         => '44',
        'magenta'      => '45',
        'cyan'         => '46',
        'light_gray'   => '47',
    );

    private static $source = array();

    public static function br($n=1) {
        return str_repeat(PHP_EOL, $n);
    }

    public static function color($string, $fg = null, $bg = null) {
        if (!Preview::$config->color_support) {
            return $string;
        }

        $colored = "";
        if (isset(self::$foreground_colors[$fg])) {
            $colored .= "\033[". self::$foreground_colors[$fg]. "m";
        }
        if (isset(self::$background_colors[$bg])) {
            $colored .= "\033[". self::$background_colors[$bg] . "m";
        }
        $colored .=  $string . "\033[0m";
        return $colored;
    }

    public static function source_lines($file, $startline, $endline) {
        if (!is_file($file)) {
            return array();
        }

        if (!isset(self::$source[$file])) {
            self::$source[$file] = file($file);
        }

        $lines = array();
        for($i = $startline; $i < $endline + 1; $i++) {
            if(!isset(self::$source[$file][$i])) {
                break;
            }

            $lines[] = self::$source[$file][$i];
        }
        return $lines;
    }

    public static function source_line($file, $line) {
        $lines = self::source_lines($file, $line, $line);
        if (count($lines) === 0) {
            return null;
        }
        return $lines[0];
    }
}
