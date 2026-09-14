# Modélisation - My Series Companion

## MCD simplifié

SERIE (1,N) -------- (1,1) SAISON

SAISON (1,N) -------- (1,1) EPISODE

PERSONNE (1,N) -------- REGARDER -------- (1,N) EPISODE


## Relations

Une série peut avoir plusieurs saisons.

Une saison appartient à une seule série.

Une saison peut avoir plusieurs épisodes.

Un épisode appartient à une seule saison.

Une personne peut regarder plusieurs épisodes.


## Tables

SERIE (
    id PK,
    nom,
    resume,
    vignette,
    date_sortie
)

SAISON (
    id PK,
    nom,
    resume,
    vignette,
    date_sortie,
    serie_id FK -> SERIE.id
)

EPISODE (
    id PK,
    nom,
    resume,
    vignette,
    date_sortie,
    duree,
    saison_id FK -> SAISON.id
)

PERSONNE (
    id PK,
    email,
    prenom
)

REGARDER (
    personne_id PK/FK -> PERSONNE.id,
    episode_id PK/FK -> EPISODE.id
)