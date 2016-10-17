# Apache Status
> Service to return JSON information representing the status of Apache on this server.

| Branch  | Environment |                                      website | status | coverage |
|---------|-------------|---------------------------------------------:|-------:|---------:|
| develop | development | https://apache-status.dev.ahss.io/ | [![build status](https://gitlab.floridahospital.org/corycollier/apache-status/badges/develop/build.svg)](https://gitlab.floridahospital.org/corycollier/apache-status/commits/develop) | [![coverage report](https://gitlab.floridahospital.org/corycollier/apache-status/badges/develop/coverage.svg)](https://gitlab.floridahospital.org/corycollier/apache-status/commits/develop)
| master  | production  | https://www.floridahospitalneuro.com/     | [![build status](https://gitlab.floridahospital.org/corycollier/apache-status/badges/master/build.svg)](https://gitlab.floridahospital.org/corycollier/apache-status/commits/master) | [![coverage report](https://gitlab.floridahospital.org/corycollier/apache-status/badges/master/coverage.svg)](https://gitlab.floridahospital.org/corycollier/apache-status/commits/master) |


## Requirements
The production instance of this Drupal site uses the following technologies:
* [php 5.6](https://php.net)

## Installation
* `composer install`
* `php -S localhost:8000 -t web/`

## Release History
* (tag: 1.0.1)
    * Added unit testing to the app.
* (tag: 1.0.0)
    * First tagged release of the app.

## Authors
| Author | Email |
|--------|------:|
| [Cory Collier](https://gitlab.floridahospital.org/u/corycollier) | <cory.collier@ahss.org> |
