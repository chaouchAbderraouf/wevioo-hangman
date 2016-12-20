#! /bin/sh

php bin/console doctrine:database:drop -n --if-exists --force
php bin/console doctrine:database:create --...
php bin/console doctrine:schema:create

php bin/console doctrine:fixtures:load

php vendor/bin/phpunit

php bin/console doctrine:database:drop -n --force
