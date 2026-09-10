# CHANGELOG.md

## 12.2.1

- Fixed `OpenAPI\Server\Validator\DateTime` on PHP 8.2 and newer. Since PHP 8.2.0
  `\DateTime::getLastErrors()` returns `false` instead of an array with zero counters when the last
  parsing produced neither errors nor warnings, so every valid value of a `format: date-time` property
  raised `Trying to access array offset on false` — a 500 behind Stratigility's error handler and a
  failing test under PHPUnit. Validation results are unchanged on every supported PHP version.

## 12.0.0

- Updated minimum version of OpenAPITools/[openapi-generator](https://github.com/OpenAPITools/openapi-generator) from
  4.x to 7.x