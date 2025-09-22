# ToDo & Co

Application de gestion de tâches avec Symfony.

## Prérequis

- PHP 8.1+
- Composer
- MySQL/PostgreSQL

## Installation

```bash
git clone [votre-repo]
composer install
cp .env .env.local
# Configurer DATABASE_URL et APP_SECRET dans .env.local
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

## Utilisation

```bash
symfony server:start
```

Accès : `http://localhost:8000`

**Comptes de test :**
- Admin : admin/admin
- User : user/user

## Tests

```bash
php bin/phpunit
php bin/phpunit --coverage-html coverage/
```