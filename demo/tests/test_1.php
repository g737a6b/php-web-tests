<?php
echo "\$var1: {$var1}\n";

// `self::$driver` is `RemoteWebDriver` object in facebook/php-webdriver.
// https://github.com/facebook/php-webdriver
$url = "http://example.com";
self::$driver->get($url);
$title = self::$driver->getTitle();
echo "Page title of {$url} is \"{$title}\".\n";
