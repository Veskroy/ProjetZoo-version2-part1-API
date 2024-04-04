# WildWonderHub (SAE 3.01)

![logo](public/assets/images/logos/LogoSAE_Zoo.png)

---

WildWonderHub est une application de gestion des visiteurs et des animaux du Zoo de la Palmyre, utilisant principalement le framework Symfony (version 6.3).

Il s'agit ici de la partie API du projet.

Le sujet sur lequel s'appuyer pour la réalisation de cette SAE se trouve [ici](http://cutrona/but/s4/sae4-real-01/).

---

## Table des matières

<!-- TOC -->
  * [Auteurs du projet](#auteurs-du-projet)
  * [Outils utilisés](#outils-utilisés)
  * [Guide d'installation](#guide-dinstallation)
  * [Gestion des branches et commits](#gestion-des-branches-et-commits)
  * [Installation avec Docker](#installation-avec-docker)
  * [Troubleshooting](#troubleshooting)
<!-- TOC -->

---

### Auteurs du projet

- Logan Jacotin
- Romain Leroy
- Vincent Kpatinde
- Clément Perrot

---

### Outils utilisés

- [Symfony](https://symfony.com/doc/current/setup.html)
- [API Platform](https://api-platform.com/docs/distribution/)
- [Docker](https://docs.docker.com/)

<i>(... à compléter)</i>

---

### Guide d'installation

1- Clônage du projet
```shell
git clone https://iut-info.univ-reims.fr/gitlab/perr0112/sae4-01-api.git
```

2- Se placer dans le projet
```shell
cd sae4-01-api
```

2- Installer toutes les dépendances
```shell
composer install
```

3- Ajouter un fichier local pour la base de données
```shell
cp .env .env.local
```

4- Définir la base de données
```shell
DATABASE_URL="mysql://user:password@mysql:3306/dbName?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
```

5- Remplir la base de données
```shell
composer db
```

6- Démarrer le projet
```shell
composer start
```

---

### Gestion des branches et commits

#### Apporter une modification au dépôt git
- Créer une branche contenant le nom de la fonctionnalité traitée

```shell
git branch <nom>
```

- Se positionner sur cette branche

```shell
git checkout <nom>
```

- Retourner sur la branche main une fois le code écrit

```shell
git checkout main
git pull
```

- Retourner sur sa branche locale
```shell
git checkout <nom>
git rebase main
```

- Push le code écrit
- Demande de merge request sur le repo gitlab (en décrivant les ajouts/modifications apportés au projet)

#### Exemple de commit

* ajout d’une fonctionnalité

```shell
git commit -m "add: <fonctionnalité ajoutée>"
```
* modification d’une fonctionnalité déjà présente
```shell
git commit -m "edit: <fonctionnalité modifiée>"
```
* suppression d’un fichier
```shell
git commit -m "delete: <fonctionnalité supprimée>"
```
* modification d'un composant
```shell
git commit -m "edit(<component>): <fonctionnalité modifiée sur le dit component>"
```

### Installation avec Docker

- Pour le lancer avec le docker compose :
```sh
docker-compose up
```

## Troubleshooting

Si vous n'avez pas les permissions executez le fichier `droits.sh`
Dans le cas de l'IUT vous pouvez executer :
```sh
docker exec -ti sae4-01-api-php-1 /bin/sh
chmod -R o+rwx public vendor
```
