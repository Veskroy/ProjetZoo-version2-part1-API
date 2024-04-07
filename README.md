# WildWonderHub (SAE 3.01)

![logo](public/assets/images/logos/LogoSAE_Zoo.png)

---

WildWonderHub est une application de gestion des visiteurs et des animaux du Zoo de la Palmyre, utilisant principalement le framework Symfony (version 6.3).

Il s'agit ici de la partie API du projet.

Le sujet sur lequel s'appuyer pour la réalisation de cette SAE se trouve [ici](http://cutrona/but/s4/sae4-real-01/).

---

## Table des matières

<!-- TOC -->
- [Auteurs du projet](#auteurs-du-projet)
- [Outils utilisés](#outils-utilisés)
- [Guide d'installation](#guide-dinstallation)
    - [Clonage du Projet](#clonage-du-projet)
    - [Installation des Dépendances](#installation-des-dépendances)
    - [Configuration de l'Environnemen Local](#configuration-de-lenvironnement-local)
    - [Configuration de la Base de Données](#configuration-de-la-base-de-données)
    - [Remplir la base de données](#remplir-la-base-de-données)
    - [Démarrage du Projet](#démarrage-du-projet)
- [Gestion des Branches et des Commits](#gestion-des-branches-et-des-commits)
- [Installation avec Docker](#installation-avec-docker)
- [Troubleshooting](#troubleshooting)
- [VM de l'API](#vm-de-lapi)
<!-- TOC -->

---

## Auteurs du projet

- Logan Jacotin
- Romain Leroy
- Vincent Kpatinde
- Clément Perrot

---

## Outils utilisés

- [Symfony](https://symfony.com/doc/current/setup.html)
- [API Platform](https://api-platform.com/docs/distribution/)
- [Docker](https://docs.docker.com/)

<i>(... à compléter)</i>

---

## Guide d'installation

### Clonage du Projet
```shell
git clone https://iut-info.univ-reims.fr/gitlab/perr0112/sae4-01-api.git
cd sae4-01-api
```

### Installation des dépendances
```shell
composer install
```

### Configuration de l'environnement local
Créez un fichier d'environnement local :
```shell
cp .env .env.local
```

### Configuration de la base de données
Définissez la configuration de la base de données dans .env.local :

```shell
DATABASE_URL="mysql://user:password@mysql:3306/dbName?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
```
DATABASE_URL pour Docker:
```shell
DATABASE_URL="mysql://WildWonderHub_user:WildWonderHub_password@db/WildWonderHub_db?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
```

### Remplir la base de données
```shell
composer db
```

### Démarrage du projet
```shell
composer start
```

---

## Gestion des branches et des commits

### Création d'une nouvelle branche

```shell
git checkout -b <nom_de_branche>
```

### Passage à la branche principale

```shell
git checkout main
git pull
```

### Rebase et push des modifications
```shell
git checkout <nom_de_branche>
git rebase main
git push origin <nom_de_branche>
```

- Push le code écrit
- Demande de merge request sur le repo gitlab (en décrivant les ajouts/modifications apportés au projet)

### Exemple de commit

* Ajout d’une fonctionnalité

```shell
git commit -m "add: <fonctionnalité ajoutée>"
```
* Modification d’une fonctionnalité déjà présente
```shell
git commit -m "edit: <fonctionnalité modifiée>"
```
* Suppression d’un fichier
```shell
git commit -m "delete: <fonctionnalité supprimée>"
```
* Modification d'un composant
```shell
git commit -m "edit(<component>): <fonctionnalité modifiée sur le dit component>"
```
---

### Installation avec docker

- Pour le lancer avec le docker compose :
```sh
docker-compose up
```
---
## Troubleshooting

Si vous rencontrez des problèmes de permissions, exécutez la commande suivante :
```sh
docker exec -ti sae4-01-api-php-1 /bin/sh
chmod -R o+rwx public vendor
```
---
## VM de l'api

Accédez à l'API sur une machine virtuelle utilisant des conteneurs Docker :
http://10.31.33.191:8085/api 
