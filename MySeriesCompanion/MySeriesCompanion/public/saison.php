<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

// Récupération de l'id de la saison
if (isset($_GET["id"])) {
    $saisonId = (int) $_GET["id"];
} else {
    header("Location: index.php");
    exit;
}

// Récupération de la saison
$sql = "SELECT saison.*, serie.nom AS nom_serie
        FROM saison
        INNER JOIN serie ON saison.serie_id = serie.id
        WHERE saison.id = :id";

$requete = $pdo->prepare($sql);
$requete->execute([
    "id" => $saisonId
]);

$saison = $requete->fetch(PDO::FETCH_ASSOC);

if (!$saison) {
    header("Location: index.php");
    exit;
}

// Récupération des épisodes
$sql = "SELECT *
        FROM episode
        WHERE saison_id = :saison_id
        ORDER BY date_sortie ASC";

$requete = $pdo->prepare($sql);
$requete->execute([
    "saison_id" => $saisonId
]);

$episodes = $requete->fetchAll(PDO::FETCH_ASSOC);

$titrePage = $saison["nom"];

require "../includes/header.php";
?>

<section class="entete-detail">

    <?php if (!empty($saison["vignette"])): ?>
        <img class="image-detail"
             src="<?= e($saison["vignette"]) ?>"
             alt="<?= e($saison["nom"]) ?>">
    <?php endif; ?>

    <div class="infos-detail">

        <h1><?= e($saison["nom"]) ?></h1>

        <p>
            Série :
            <strong><?= e($saison["nom_serie"]) ?></strong>
        </p>

        <?php if (!empty($saison["date_sortie"])): ?>
            <p class="date">
                Date de sortie : <?= e($saison["date_sortie"]) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($saison["resume"])): ?>
            <p class="resume">
                <?= e($saison["resume"]) ?>
            </p>
        <?php endif; ?>

        <a class="bouton bouton-secondaire"
           href="serie.php?id=<?= $saison["serie_id"] ?>">
            Retour à la série
        </a>

    </div>

</section>

<div class="titre-section">

    <h2>Épisodes</h2>

    <a class="bouton"
       href="ajouter-episode.php?saison_id=<?= $saisonId ?>">
        + Ajouter un épisode
    </a>

</div>

<?php if (empty($episodes)): ?>

    <div class="vide">
        <p>Aucun épisode pour cette saison.</p>
    </div>

<?php else: ?>

    <div class="liste-episodes">

        <?php foreach ($episodes as $episode): ?>

            <article class="episode">

                <h3><?= e($episode["nom"]) ?></h3>

                <?php if (!empty($episode["date_sortie"])): ?>
                    <p class="date">
                        Sortie : <?= e($episode["date_sortie"]) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($episode["duree"])): ?>
                    <p>
                        Durée : <?= e($episode["duree"]) ?> minutes
                    </p>
                <?php endif; ?>

                <?php if (!empty($episode["resume"])): ?>
                    <p class="resume">
                        <?= e($episode["resume"]) ?>
                    </p>
                <?php endif; ?>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php require "../includes/footer.php"; ?>