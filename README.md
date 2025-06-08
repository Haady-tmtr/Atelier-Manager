## Atelier Manager



L'objectif de ce projet est de développer une application web complète avec le 
framework Symfony, permettant de gérer des **ateliers pédagogiques**, avec un système 
d'authentification, des rôles (élévation ou regression), des inscriptions, et une visualisation des satisfactions via des graphiques.


### Technologies utilisées

- PHP 8.3 / Symfony 6.x
- Webpack Encore
- Bootstrap 5
- SQLite (portabilité)
- Faker (fixtures), pour remplir la base de données et tester rapidement
- Markdown support
- Chart.js (via `symfony/ux-chartjs`)

### Fonctionnalités principales

- Création/gestion d'ateliers (CRUD)
- Authentification avec rôles (`ADMIN`, `INSTRUCTEUR`, `APPRENTI`)
- Système d'inscription / désinscription aux ateliers
- Attribution d’un instructeur par atelier
- Gestion des accès selon les rôles
- Rendu des descriptions en Markdown
- Statistiques de satisfaction avec graphiques


### Identifiants de quelques utilisateurs pour se connecter et tester :
email            ======>      mot de passe

admin@gmail.com  ======>      secret

toto@gmail.com  ======>       secret

paul.durand@example.com ======>   secret

### Démarrage rapide

```
# Installation des dépendances
composer install
npm install && npm run dev

# Création de la base de données
symfony console doctrine:database:create  

symfony console make:migration

symfony console doctrine:migrations:migrate

# Chargement des fixtures
symfony console doctrine:fixtures:load

# Lanncer le serveur
symfony server:start --no-tls --listen-ip=0.0.0.0 --d
```


Des problèmes avec des dépendances peuvent survenir, donc refaire ces commandes en cas de soucis :


symfony composer require symfony/webpack-encore-bundle    
symfony composer require orm-fixtures --dev   
symfony composer require fakerphp/faker  
symfony composer require cebe/markdown (a déjà posé problème)  
symfony composer require symfony/ux-chartjs (idem).