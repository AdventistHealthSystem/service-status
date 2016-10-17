#!/bin/bash
#
cd ./web
./../vendor/bin/drush site-install --verbose --yes --db-url=sqlite://tmp/site.sqlite
./../vendor/bin/drush runserver http://127.0.0.1:8080 &
sleep 3
./../vendor/bin/phpunit -c core --testsuite unit --exclude-group Composer,PageCache --coverage-text --colors=never
# Skip core/tests/Drupal/Tests/ComposerIntegrationTest.php because web/ has no composer.json
./../vendor/bin/drush
./../vendor/bin/drupal