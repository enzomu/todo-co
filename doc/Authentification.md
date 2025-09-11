# Authentification ToDo & Co 

L'utilisateur va sur `/login`, saisit son username/password, et Symfony vérifie automatiquement.

 Fichiers importants

1. Configuration (`config/packages/security.yaml`)

 2. Entité User (`src/Entity/User.php`)
- Implémente `UserInterface` et `PasswordAuthenticatedUserInterface`
- Champs : username, email, password (hashé), roles
- Méthode `getUserIdentifier()` retourne le username

 3. Authenticator (`src/Security/LoginFormAuthenticator.php`)
- Récupère username/password du formulaire
- Crée un Passport avec UserBadge + PasswordCredentials
- Symfony compare automatiquement les mots de passe
- Redirige vers homepage après connexion

 4. Controller (`src/Controller/SecurityController.php`)
- Route `/login` : affiche le formulaire
- Route `/logout` : déconnexion automatique

5. Fixtures `src/DataFixtures/UserFixtures.php` :
- **anonyme** / password (pour les anciennes tâches)
- **admin** / admin (administrateur)
- **user** / user (utilisateur normal)

 Vérification du mot de passe

Symfony fait tout automatiquement :
1. L'authenticator crée un `PasswordCredentials`
2. Symfony récupère l'utilisateur par username
3. Compare le mot de passe saisi avec le hash en base
4. Utilise l'algorithme configuré (auto = bcrypt/argon2i)

Templates

- `templates/security/login.html.twig` : formulaire de connexion
- `templates/base.html.twig` : navigation avec liens connexion/déconnexion


 Pour modifier

- **Ajouter un champ à User** : Modifier l'entité + migration
- **Changer les règles d'accès** : Modifier security.yaml
- **Modifier la redirection** : Modifier LoginFormAuthenticator