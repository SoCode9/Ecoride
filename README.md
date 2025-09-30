# EcoRide
EcoRide est une application web de covoiturage conçue pour faciliter la mise en relation entre conducteurs et passagers, dans une démarche écologique et économique.

Ce dépôt contient le code source du projet, incluant un frontend (HTML/CSS/JS) et un backend en PHP, avec une base de données MySQL et une base de données NoSQL MongoDB.

---

## Prérequis
Avant de déployer l’application en local, assurez-vous d’avoir installé :
- Docker Desktop
- Git
- Un navigateur web moderne

Toutes les autres dépendances (PHP, Apache, MySQL, MongoDB, Mailpit, etc.) sont gérées par les conteneurs Docker.

## Installation 
### 1. Cloner le dépôt
```bash
git clone https://github.com/SoCode9/Ecoride.git
```

### 2. Créer le fichier .env
À la racine du projet, créer un fichier .env (non versionné) contenant vos paramètres :
```bash
APP_ENV=local

# MySQL
DB_HOST=db
DB_PORT=3306
DB_NAME=ecoride
DB_USER=votreuser
DB_PASS=votremdp
DB_CHARSET=utf8mb4
DB_ROOT_PASSWORD=rootpass

# Mongo
MONGO_ROOT_USER=root
MONGO_ROOT_PASS=supersecret
MONGO_DB=ecoride
MONGO_URI=mongodb://root:supersecret@mongo:27017/ecoride?authSource=admin

# mongo-express
ME_BASIC_USER=admin
ME_BASIC_PASS=adminpass

# Mail (Mailpit)
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM=no-reply@ecoride.local
MAIL_FROM_NAME=EcoRide
```

### 3. Démarrer les conteneurs
```bash
docker compose up --build
```
> Les services démarrés sont :
>- Application PHP/Apache → http://localhost:8080
>- phpMyAdmin → http://localhost:8081
>- Mongo Express → http://localhost:8082
>- Mailpit → http://localhost:8025

### 4. Installer les dépendances PHP
Dans le conteneur applicatif :
```bash
docker exec -it ecoride-app bash
composer install
```

### 5. Installer les tables SQL
Lancer le script pour créer les tables :
```bash
docker exec -i ecoride-db mysql -uvotreuser -pvotremdp ecoride < ecoride.sql
```

## Envoi d'emails
Les emails envoyés par l’application sont interceptés par Mailpit (service SMTP de test).
- Interface : http://localhost:8025
- Paramètres SMTP : host=mailpit, port=1025.

## Démarrer l'application
Une fois les services démarrés, l’application est accessible à l’adresse :
> http://localhost:8080
