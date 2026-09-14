# My Series Companion

Projet réalisé en BTS SIO SLAM (2ème année)

## Fonctionnalités

- Afficher les séries ajoutées
- Ajouter une nouvelle série
- Consulter le détail d'une série
- Ajouter une saison à une série
- Consulter le détail d'une saison
- Ajouter un épisode à une saison
- Afficher les épisodes d'une saison
- Enregistrer les données dans une base MySQL

Le design du site est simple avec des couleurs noires, blanches et grises.

## Arborescence

MySeriesCompanion/

public/
    - index.php
    - ajouter-serie.php
    - serie.php
    - ajouter-saison.php
    - saison.php
    - ajouter-episode.php

    assets/
        css/
            - style.css

config/
    - database.php

includes/
    - functions.php
    - header.php
    - footer.php

database/
    - my_series_companion.sql
    - modelisation.md


## Base de données

Les données sont enregistrées dans MySQL avec PHP et PDO.

Par exemple, pour ajouter une série :

INSERT INTO serie (nom, resume, vignette, date_sortie)
VALUES (:nom, :resume, :vignette, :date_sortie)

Les saisons sont reliées aux séries grâce à serie_id.

Les épisodes sont reliés aux saisons grâce à saison_id.


## Test du projet

1. Aller sur la page d'accueil.
2. Cliquer sur "+ Ajouter une série".
3. Remplir le formulaire.
4. Valider l'ajout.
5. Vérifier que la série apparaît.
6. Ajouter une saison à la série.
7. Ajouter un épisode à la saison.
8. Vérifier les données dans phpMyAdmin.