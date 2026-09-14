<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

// Récupération de l'id de la série
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
} else {
    header("Location: index.php");
    exit;
}

// Récupération de la série
$requete = $pdo->prepare("SELECT * FROM serie WHERE id = :id");
$requete->execute([
    "id" => $id
]);

$serie = $requete->fetch(PDO::FETCH_ASSOC);

if (!$serie) {
    header("Location: index.php");
    exit;
}

// Récupération des saisons
$sql = "SELECT *
        FROM saison
        WHERE serie_id = :serie_id
        ORDER BY date_sortie ASC";

$requete = $pdo->prepare($sql);
$requete->execute([
    "serie_id" => $id
]);

$saisons = $requete->fetchAll(PDO::FETCH_ASSOC);

$titrePage = $serie["nom"];

require "../includes/header.php";
?>

<section class="entete-detail">

    <div>

        <?php if (!empty($serie["vignette"])): ?>
            <img class="image-detail"
                 src="<?= e($serie["vignette"]) ?>"
                 alt="<?= e($serie["nom"]) ?>">
        <?php endif; ?>

    </div>

    <div class="infos-detail">

        <h1><?= e($serie["nom"]) ?></h1>

        <p class="date">
            Date de sortie :
            <?= date("d/m/Y", strtotime($serie["date_sortie"])) ?>
        </p>

        <?php if (!empty($serie["resume"])): ?>
            <p class="resume">
                <?= nl2br(e($serie["resume"])) ?>
            </p>
        <?php endif; ?>

        <a class="bouton bouton-secondaire" href="index.php">
            ← Retour aux séries
        </a>

    </div>

</section>

<div class="titre-section">

    <div>
        <h2>Les saisons</h2>
        <p class="date">
            Nombre de saisons : <?= count($saisons) ?>
        </p>
    </div>

    <a class="bouton"
       href="ajouter-saison.php?serie_id=<?= $serie["id"] ?>">
        + Ajouter une saison
    </a>

</div>

<?php if (count($saisons) === 0): ?>

    <div class="vide">
        Aucune saison n'est encore présente pour cette série.
    </div>

<?php else: ?>

    <div class="grille-saisons">

        <?php foreach ($saisons as $saison): ?>

            <article class="carte">

                <?php if (!empty($saison["vignette"])): ?>
                    <img class="image-carte"
                         src="<?= e($saison["vignette"]) ?>"
                         alt="<?= e($saison["nom"]) ?>">
                <?php endif; ?>

                <div class="contenu-carte">

                    <h3><?= e($saison["nom"]) ?></h3>

                    <p class="date">
                        Sortie :
                        <?= date("d/m/Y", strtotime($saison["date_sortie"])) ?>
                    </p>

                    <?php if (!empty($saison["resume"])): ?>
                        <p class="resume">
                            <?= nl2br(e($saison["resume"])) ?>
                        </p>
                    <?php endif; ?>

                    <a class="bouton bouton-secondaire"
                       href="saison.php?id=<?= $saison["id"] ?>">
                        Voir la saison
                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php require "../includes/footer.php"; ?>