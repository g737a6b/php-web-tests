<?php
declare(strict_types=1);
namespace Mofg;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * PHPWebTests
 *
 * @author Hiroyuki Suzuki
 * @copyright Copyright (c) 2019 Hiroyuki Suzuki mofg.net
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version 1.0.0
 */
class WebTests{
	/**
	 * @var array
	 */
	public static $config = [];

	/**
	 * @var array
	 */
	public static $tests = [];

	/**
	 * @var object
	 */
	public static $driver;

	/**
	 * @param array $config
	 * @param array $options
	 * @return void
	 */
	public static function setup(array $config, array $options) : void{
		self::$config = $config;

		$groupName = isset($options["g"]) ? $options["g"] : "all";
		if( !isset($config["groups"][$groupName]) ){
			fwrite(STDERR, "{$groupName} group is not defined.".PHP_EOL);
			exit(1);
		}
		self::log("Run tests about {$groupName} group.");

		self::$tests = $config["groups"][$groupName];
		if( self::$tests === "*" ){
			self::$tests = [];
			foreach(scandir($config["tests_dir"]) as $i){
				$file = $config["tests_dir"]."/".$i;
				if( is_dir($file) || !is_readable($file) ) continue;
				self::$tests[] = preg_replace("/\.php$/", "", $i);
			}
		}

		$host = "http://localhost:4444/wd/hub";
		self::$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
	}

	/**
	 * @return void
	 */
	public static function runTests() : void{
		$counter = 0;
		foreach(self::$tests as $i){
			self::runSingle($i, $counter);
		}
		if( $counter > 0 ){
			self::log($counter." / ".count(self::$tests)." tests passed.", "", "ok");
		}
		self::$driver->quit();
	}

	/**
	 * @param string $name
	 * @param integer $passed
	 * @return void
	 */
	public static function runSingle(string $name, int &$passed) : void{
		self::log("Testing {$name}...");
		try{
			if( !empty(self::$config["vars"]) ){
				extract(self::$config["vars"], EXTR_SKIP);
			}
			include(self::$config["tests_dir"]."/{$name}.php");
		}catch(\Exception $e){
			self::log("Test failed. ".$e->getMessage(), "", "error");
			return;
		}
		$passed++;
	}

	/**
	 * @param string $message
	 * @param string $label
	 * @param string $mode
	 * @return void
	 */
	public static function log(string $message, string $label = "LOG", string $mode = "default") : void{
		$colorStart = "";
		switch(strtolower($mode)){
			case "success":
			case "ok":
				$colorStart = "\033[32m";
				break;
			case "failure":
			case "error":
			case "ng":
				$colorStart = "\033[31m";
				break;
		}
		$label = !empty($label) ? "[{$label}] " : "";
		$colorEnd = "\033[0m";
		fwrite(STDOUT, "{$colorStart}{$label}{$message}{$colorEnd}".PHP_EOL);
	}
}
