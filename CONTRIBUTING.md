# Comment contribuer

## Workflow

1. **Fork** le projet
2. **Créer** une branche : `git checkout -b feature/nouvelle-fonctionnalite`
3. **Développer** en suivant les règles ci-dessous
4. **Tester** : `php bin/phpunit`
5. **Commit** : `git commit -m "feat: description"`
6. **Push** : `git push origin feat/nouvelle-fonctionnalite`
7. **Pull Request** vers `main`

## Règles de développement

### Code
- Suivre PSR-12
- Respect des conventions Symfony

### Tests
- Couverture minimum : 70%
- Tests unitaires pour les entités/services
- Tests fonctionnels pour les contrôleurs
- Tous les tests doivent passer

### Commits
Format : `feat/TODO-CO: description`

Types :
- `feat` : nouvelle fonctionnalité
- `fix` : correction de bug
- `test` : ajout/modification tests
- `docs` : documentation
- `refactor` : refactoring

### Pull Requests
- 1 PR = 1 fonctionnalité
- Description claire
- Tests qui passent

## Outils qualité

```bash
# Tests
php bin/phpunit

# Coverage
php bin/phpunit --coverage-html coverage/
ouvrir dans le navigateur 'coverage/index.html'
```