# EcoRide
EcoRide est une application web de covoiturage conçue pour faciliter la mise en relation entre conducteurs et passagers, dans une démarche écologique et économique.

Ce dépôt contient le code source du projet, incluant un frontend (HTML/CSS/JS) et un backend en PHP, avec une base de données MySQL.

---

## Prérequis
Avant de déployer l’application en local, assurez-vous d’avoir les éléments suivants installés :

- PHP >= 8.2
- MySQL >= 5.7
- Un serveur local (comme XAMPP)
- MongoDB
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
>Par exemple, vous obtiendrez : C:\xampp\htdocs\EcoRide
>Ensuite, vous pourrez y accéder via : http://localhost/EcoRide

### 3. Installer l'extension intl de php
Ouvrir le fichier php.ini et ajouter l'extension, qui fournit des outils pour le formatage des dates notamment.
```bash
extension=php_intl.dll
```
Penser à redémarrer le serveur (Apache) pour charger l'extension.

### 4. Créer la base de données SQL
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
3. Créer un compte administrateur par défaut (voir script)

#### b. Données de test (optionnel)
Le fichier `ecoride_test_data.sql` permet de remplir la base avec des utilisateurs fictifs, trajets et réservations pour tester l’application sans tout créer manuellement.

Importez le fichier fichier SQL situé à la racine du projet : `ecoride_test_data.sql` :

- toujours dans phpMyAdmin, cliquez sur l’onglet Importer
- sélectionnez le fichier `ecoride_test_data.sql`
- cliquez sur Exécuter

Ou en ligne de commande :
```bash
mysql -u root -p ecoride < ecoride_test_data.sql
```
> Consulter le contenu du fichier pour voir les utilisateurs et trajets ajoutés.
> Les mots de passe des utilisateurs sont : `Test1234!`

### 5. Base NoSQL MongoDB
1. Ouvrir MongoDB 
2. Créer une base nommée `ecoride`
3. Créer une collection nommée `preferences`
4. Activer l'extension MongoDB `extension=mongodb` dans `php.ini`
5. Exécuter côté PHP
```bash
composer require mongodb/mongodb
```

## Configuration
À la racine, créer un fichier .env (non commité) avec vos paramètres locaux. 
Exemple : 

```bash
APP_ENV=local
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=ecoride
DB_USER=admin_php
DB_PASS=Test1234!
DB_CHARSET=utf8mb4

MONGO_URI=mongodb://localhost:27017
MONGO_DB=ecoride
```

### Envoi d'emails (MailHog)
Pour intercepter et visualiser les emails en local (sans envoyer de vrais mails), nous utilisons MailHog via Docker.
Etapes d'installation : 
1. Lancer MailHog avec Docker
```bash
docker run -d --name mailhog -p 1025:1025 -p 8025:8025 mailhog/mailhog
```
2. Accéder à l'interface MailHog : http://localhost:8025
3. Si l'envoi d'email ne fonctionne pas, ajouter cela dans le fichier php.ini :
```ini
[mail function]
SMTP = localhost
smtp_port = 1025
sendmail_from = test@ecoride.local
```

## Démarrer l'application
1. Ouvrir le panneau de contrôle XAMPP
2. Démarrer Apache et MySQL
3. Accéder à l’application dans votre navigateur à l’adresse :

**http://localhost/EcoRide**

