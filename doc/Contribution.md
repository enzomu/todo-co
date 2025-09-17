Comment contribuer



Créer une branche : git checkout -b feat/todo-co/nouvelle-fonctionnalite
Développer en suivant les règles ci-dessous
Tester : php bin/phpunit
Commit : git commit -m "feat: description"
Push : git push 
Pull Request vers main

Règles de développement

Suivre PSR-12
Respect des conventions Symfony

Tests

Couverture minimum : 70%
Tests unitaires 
Tests fonctionnels (controllers)
Tous les tests doivent passer



Outils qualité

php bin/phpunit

php bin/phpunit --coverage-html coverage/
