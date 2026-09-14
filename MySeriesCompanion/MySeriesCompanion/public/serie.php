<?php
// Connexion à la base de données et fonctions
require_once "../config/database.php";
require_once "../includes/functions.php";

// Récupérer l'ID
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

// Récupérer la série
$requete = $pdo->prepare("SELECT * FROM serie WHERE id = :id");
$requete->execute(["id" => $id]);
$serie = $requete->fetch(PDO::FETCH_ASSOC);

// Si la série n'existe pas
if (!$serie) {
    header("Location: index.php");
    exit;
}

// Récupération des saisons
$requete = $pdo->prepare(
    "SELECT * FROM saison
     WHERE serie_id = :serie_id
     ORDER BY date_sortie ASC"
);
$requete->execute(["serie_id" => $id]);
$saisons = $requete->fetchAll(PDO::FETCH_ASSOC);

$titrePage = $serie["nom"];
require "../includes/header.php";
?>

<section class="detail-header">

    <div>
        <?php if (!empty($serie["vignette"])): ?>
            <img
                class="detail-image"
                src="<?= e($serie["vignette"]) ?>"
                alt="Vignette de <?= e($serie["nom"]) ?>"
            >
        <?php endif; ?>
    </div>

    <div class="detail-info">
        <h1><?= e($serie["nom"]) ?></h1>

        <p class="date">
            Date de sortie :
            <?= date("d/m/Y", strtotime($serie["date_sortie"])) ?>
        </p>

        <?php if (!empty($serie["resume"])): ?>
            <p class="resume"><?= nl2br(e($serie["resume"])) ?></p>
        <?php endif; ?>

        <a class="btn btn-secondary" href="index.php">← Retour aux séries</a>
    </div>

</section>

<div class="section-title">
    <div>
        <h2>Les saisons</h2>
        <p class="date">Nombre de saisons : <?= count($saisons) ?></p>
    </div>

    <!-- Bouton pour ajouter une saison à cette série -->
    <a class="btn" href="ajouter-saison.php?serie_id=<?= $serie["id"] ?>">
        + Ajouter une saison
    </a>
</div>

<?php if (count($saisons) === 0): ?>

    <!-- Message si aucune saison n'a été ajoutée -->
    <div class="empty">
        Aucune saison n'est encore présente pour cette série.
    </div>

<?php else: ?>

    <div class="seasons-grid">

        <?php foreach ($saisons as $saison): ?>

            <article class="card">

                <?php if (!empty($saison["vignette"])): ?>
                    <img
                        class="card-image"
                        src="<?= e($saison["vignette"]) ?>"
                        alt="Vignette de <?= e($saison["nom"]) ?>"
                    >
                <?php endif; ?>

                <div class="card-content">

                    <h3><?= e($saison["nom"]) ?></h3>

                    <p class="date">
                        Sortie : <?= date("d/m/Y", strtotime($saison["date_sortie"])) ?>
                    </p>

                    <?php if (!empty($saison["resume"])): ?>
                        <p class="resume"><?= nl2br(e($saison["resume"])) ?></p>
                    <?php endif; ?>

                    <a class="btn btn-secondary"
                       href="saison.php?id=<?= $saison["id"] ?>">
                        Voir la saison
                    </a>

                </div>
            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php require "../includes/footer.php"; ?>