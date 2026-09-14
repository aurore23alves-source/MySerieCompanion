# My Series Companion

Projet BTS SIO SLAM - niveau BTS 1.

## Fonctionnalités

- afficher les séries ajoutées ;
- ajouter une série avec le bouton **+ Ajouter une série** ;
- enregistrer réellement la série dans MySQL ;
- consulter le détail d'une série ;
- ajouter une saison liée automatiquement à la série ;
- consulter le détail d'une saison ;
- ajouter un épisode lié automatiquement à la saison ;
- afficher un message lorsqu'une saison ne possède aucun épisode.

Le design est volontairement simple : noir, blanc et gris.

## Arborescence

```text
MySeriesCompanion/
├── public/
│   ├── index.php
│   ├── ajouter-serie.php
│   ├── serie.php
│   ├── ajouter-saison.php
│   ├── saison.php
│   ├── ajouter-episode.php
│   ├── assets/css/style.css
│   └── uploads/
├── config/database.php
├── includes/
│   ├── functions.php
│   ├── header.php
│   └── footer.php
└── database/
    ├── my_series_companion.sql
    └── modelisation.md
```

## Installation avec XAMPP

1. Démarrer **Apache** et **MySQL**.
2. Copier `MySeriesCompanion` dans `C:\xampp\htdocs\`.
3. Ouvrir phpMyAdmin.
4. Importer `database/my_series_companion.sql`.
5. Ouvrir :

```text
http://localhost/MySeriesCompanion/public/
```

## Enregistrement dans MySQL

Quand le formulaire `public/ajouter-serie.php` est envoyé, PHP récupère les données et exécute un `INSERT` dans la table `serie` :

```sql
INSERT INTO serie (nom, resume, vignette, date_sortie)
VALUES (:nom, :resume, :vignette, :date_sortie)
```

La requête est exécutée avec PDO. La série est donc réellement enregistrée dans MySQL.

Puis `lastInsertId()` récupère l'identifiant créé et le site redirige vers le détail de la nouvelle série.

Le même principe est utilisé pour les saisons et les épisodes.

## Pourquoi `public/` ?

`public/` contient les fichiers accessibles depuis le navigateur. Les fichiers de connexion à MySQL et les fonctions communes restent en dehors de ce dossier.

## Modélisation

```text
SERIE
   │ 1,N
   ↓
SAISON
   │ 1,N
   ↓
EPISODE

PERSONNE
   │
   ↓
REGARDER
   ↑
   │
EPISODE
```

## Test

1. Aller sur la page d'accueil.
2. Cliquer sur **+ Ajouter une série**.
3. Remplir le formulaire et valider.
4. Vérifier la redirection vers le détail.
5. Vérifier dans phpMyAdmin que la nouvelle ligne est dans `serie`.
6. Revenir à l'accueil : la série ajoutée doit maintenant apparaître.
7. Ajouter une saison puis un épisode.
