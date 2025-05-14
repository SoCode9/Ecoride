# Ecoride
Ecoride est une application web de covoiturage conçue pour faciliter la mise en relation entre conducteurs et passagers, dans une démarche écologique et économique.

Ce dépôt contient le code source du projet, incluant un frontend (HTML/CSS/JS) et un backend en PHP, avec une base de données MySQL.

---

## Prérequis
Avant de déployer l’application en local, assurez-vous d’avoir les éléments suivants installés :

- PHP >= 8.0
- MySQL >= 5.7
- Un serveur local (comme XAMPP)
- Un navigateur web moderne

## Installation 
### 1. Cloner le dépôt
```bash
git clone https://github.com/SoCode9/Ecoride.git
```

### 2. Placer le dossier dans XAMPP
Copiez le dossier du projet dans :
```bash
C:\xampp\htdocs\
```
>Par exemple, vous obtiendrez : C:\xampp\htdocs\Ecoride
>Ensuite, vous pourrez y accéder via : http://localhost/Ecoride

### 3. Créer la base de données
#### a. Structure + données essentielles
1. Ouvrir phpMyAdmin ou votre outil MySQL 
2. Créer une base nommée `ecoride`
3. Importez le fichier SQL situé à la racine du projet : `ecoride.sql`

    a. Via phpMyAdmin : 
- aller dans l'onglet Importer
- sélectionnez le fichier `ecoride.sql`
- cliquez sur Exécuter

    b. En ligne de commande MySQL
```bash
mysql -u root -p ecoride < ecoride.sql
```

Ce fichier va :

1. Créer toutes les tables nécessaires (users, roles, travels, etc.)
2. Ajouter les rôles de base (passenger, driver, etc.) et quelques marques de voitures (table `brands`)
3. Créer un compte administrateur par défaut avec :

     a. Email : `admin@test.com`

   b. Mot de passe : `Z4spU12!?`

    c. Rôle : administrateur (id_role = 5)

#### b. Données de test (optionnel)
Le fichier `ecoride_test_data.sql` permet de remplir la base avec des utilisateurs fictifs, trajets et réservations pour tester l’application sans tout créer manuellement.
- toujours dans phpMyAdmin, cliquez sur l’onglet Importer
- sélectionnez le fichier `ecoride_test_data.sql`
- cliquez sur Exécuter

Ou en ligne de commande :
```bash
mysql -u root -p ecoride < ecoride_test_data.sql
```
> Consulter le contenu du fichier pour voir les utilisateurs et trajets ajoutés.
> Les mots de passe sont déjà hachés et prêts à l'emploi.

## Configuration
L'application détecte automatiquement si elle s'exécute en local ou en production grâce à l'adresse du serveur ($_SERVER['HTTP_HOST']).

En local, elle utilise :
- `localhost` ou `127.0.0.1`
- port `3308`
- utilisateur : `root`
- mot de passe (vide)
- base de données : `ecoride`

En production (AlwaysData) :
- Configuration pour AlwaysData incluse dans le code

> Aucune configuration manuelle supplémentaire n’est requise.  

## Démarrer l'application
1. Ouvrir le panneau de contrôle XAMPP
2. Démarrer Apache et MySQL
3. Accéder à l’application dans votre navigateur à l’adresse :

**http://localhost/Ecoride**

