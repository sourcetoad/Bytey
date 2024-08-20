# Bytey
_A simple implementation of [Google's Polyline Encoding](https://developers.google.com/maps/documentation/utilities/polylinealgorithm) algorithm in PHP._

[![main](https://github.com/sourcetoad/Bytey/actions/workflows/main.yml/badge.svg)](https://github.com/sourcetoad/Bytey/actions/workflows/main.yml) [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

### Install
This package is currently supporting PHP 8.2 and above.

```shell
composer require sourcetoad/bytey
```

### Documentation

#### Encoding
```php
use Sourcetoad\Bytey;

$points = [
    [38.5, -120.2],
    [40.7, -120.95],
    [43.252, -126.453]
];

$encoded = Bytey::googlePolylineEncode($points);
// Outputs "_p~iF~ps|U_ulLnnqC_mqNvxq`@"
```