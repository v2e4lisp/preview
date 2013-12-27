<?php

namespace Preview;

/**
 * Preview class
 * containing world object and configuration object.
 *
 * @package Preview;
 */
class Preview {
    /**
     * Test world object.
     *
     * @var object $world
     */
    public static $world;

    /**
     * Configuration object.
     *
     * @var object $config;
     */
    public static $config;

    /**
     * Preview package version.
     *
     * @var string $version
     */
    public static $version = "1.0";

    /**
     * Check if php version is 5.3.
     *
     * @param null
     * @retrun bool
     */
    public static function php_version_is_53() {
        return version_compare(phpversion(), '5.4', '<');
    }
}