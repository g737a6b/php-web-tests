# PHPWebTests

PHP Web UI testing Framework.

## Examples of use

See `demo/`.

```sh
ls . | grep -e chromedriver -e php-web-tests -e selenium-server-standalone
# chromedriver
# php-web-tests.json
# selenium-server-standalone-#.jar

java -jar selenium-server-standalone-#.jar

vendor/bin/php-web-tests -g all
```

## Installation

### Composer

Add a dependency to your project's `composer.json` file.

```json
{
	"require": {
		"g737a6b/php-web-tests": "*"
	}
}
```

## License

[The MIT License](http://opensource.org/licenses/MIT)

Copyright (c) 2019 [Hiroyuki Suzuki](https://mofg.net)
