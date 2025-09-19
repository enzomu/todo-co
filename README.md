# Installation

git clone
composer install
cp .env .env.local

# DATABASE

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

# Utilisation

symfony server:start
Accès : http://localhost:8000
Comptes de test :

Admin : admin@todo.co / admin
User : user@todo.co / user

Tests
bashphp bin/phpunit
php bin/phpunit --coverage-html coverage/