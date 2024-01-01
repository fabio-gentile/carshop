
# Projet d'examen Symfony : Carshop

![Miniature carshop](https://fabiogentile.me/thumbnail/carshop.png)

## Introduction

L'objectif mon examen de Symfony était de créer une application web pour gérer un garage automobile. L'application permet aux utilisateurs de se connecter, créer un compte, et utiliser un ensemble de fonctionnalités CRUD pour gérer leur profil ainsi que les annonces liées au garage.

## Technologies utilisées

- **Symfony** `v6.3`
- **Twig**
- **Bootstrap** 
- **Javascript** 
- **mySQL** 

### Librairies externes utilisées

- [KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle) pour la pagination.
-  [Slugify](https://github.com/cocur/slugify) pour créer le slug des annonces.
-  [Faker](https://github.com/FakerPHP/Faker) pour générer des données fictives.

## Fonctionnalités principales

### 1. Authentification

L'application offre un système d'authentification permettant aux utilisateurs de se connecter à leur compte existant ou de créer un nouveau compte.

### 2. Gestion des comptes

- **Création de Compte :** Les utilisateurs ont la possibilité de créer un nouveau compte en fournissant les informations nécessaires telles que nom, adresse e-mail, et mot de passe.
- **Connexion :** Une fois inscrits, les utilisateurs peuvent se connecter en utilisant leur adresse e-mail et leur mot de passe.
- **Modification de Profil :** Les utilisateurs peuvent modifier les détails de leur profil, y compris leur nom, avatar,  adresse e-mail, et autres informations personnelles.
- **Modification de Mot de Passe :** Les utilisateurs ont la possibilité de changer leur mot de passe pour renforcer la sécurité de leur compte.

### 3. Gestion des annonces

L'application offre un ensemble complet d'opérations CRUD pour gérer les annonces liées au garage automobile.

- **Liste des Annonces :** Les utilisateurs peuvent consulter la liste complète des annonces disponibles.
- **Ajout d'Annonce :** Les utilisateurs peuvent créer de nouvelles annonces en fournissant des détails tels que le modèle, la description, le prix, etc.
- **Lecture d'Annonce :** Les détails complets d'une annonce peuvent être consultés individuellement.
- **Modification d'Annonce :** Les utilisateurs ont la possibilité de modifier les détails d'une annonce existante.
- **Suppression d'Annonce :** Les annonces peuvent être supprimées de la base de données.

### 4. Gestion des rôles  

-   **Permissions Administratives :** Les administrateurs ont des permissions étendues, comme la capacité à modifier ou supprimer des annonces.


## Prérequis 
Assurez-vous que votre environnement de développement répond aux exigences suivantes : 
- PHP (version 8.1 ou supérieure) 
- Composer 
- Node.js et npm

## Instructions d'installation local

1. Clonez le dépôt Git : `git clone https://github.com/fabio-gentile/carshop.git`  
2. Installez les dépendances PHP : `composer install`  
3. Installez les dépendances JavaScript : `npm install`  
4. Créez la base de données dans le fichier `.env` `php bin/console doctrine:database:create`
5. Exécutez les migrations : `php bin/console doctrine:migrations:migrate`  
6. (Optionnel) Générez des données fictives `php bin/console doctrine:fixtures:load`
7. Lancez le serveur de développement : `symfony server:start`

---
**Note :** Les données présentes sur le site sont purement fictives et ont été créées à des fins de démonstration. Les images utilisées ne sont pas la propriété de l'auteur de ce projet et sont utilisées à des fins illustratives uniquement.
