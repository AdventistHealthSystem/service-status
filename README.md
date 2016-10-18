# Service Status
> Service to return JSON information representing the status services on this server.

| Branch  | status | coverage |
|---------|-------:|---------:|
| develop |[![Build Status](https://travis-ci.org/corycollier/service-status.svg?branch=develop)](https://travis-ci.org/corycollier/service-status)| [![Coverage Status](https://coveralls.io/repos/github/corycollier/service-status/badge.svg?branch=develop)](https://coveralls.io/github/corycollier/service-status?branch=develop) |
| master  | [![Build Status](https://travis-ci.org/corycollier/service-status.svg?branch=master)](https://travis-ci.org/corycollier/service-status)| [![Coverage Status](https://coveralls.io/repos/github/corycollier/service-status/badge.svg?branch=master)](https://coveralls.io/github/corycollier/service-status?branch=master) |

## Requirements
This library requires at least:
* [php 5.6](https://php.net)

## Usage
If you want to check the apache virtual hosts being served:
```php
// Get the virtual hosts running on this server
$apacheService = new ServerStatus\Service\Apache;
$vhosts = $apacheService->getVhosts();
```

If you'd like to get a list of projects you have access to in Gitlab
```php
// Connect to Gitlab and get the projects
$gitlabService = new ServerStatus\Service\Gitlab([
    'private-token' => 'your-private-token-value',
]);
$projects = $gitlabService->getProjects();
```

## Authors
| Author | Email |
|--------|------:|
| [Cory Collier](https://github.com/corycollier) | <corycollier@corycollier.com> |
