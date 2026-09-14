<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

$titrePage = "Mes séries";

$requete = $pdo->query("SELECT * FROM serie ORDER BY date_sortie DESC");
$series = $requete->fetchAll(PDO::FETCH_ASSOC);

require "../includes/header.php";
?>

<section class="titre-page">
    <h1>Mes séries</h1>
    <p>Cette application te permet de gérer tes séries, leurs saisons et leurs épisodes.</p>
</section>

<div class="action-accueil">
    <a class="bouton" href="ajouter-serie.php">+ Ajouter une série</a>
</div>

<?php if (count($series) > 0): ?>

    <div class="grille-series">

        <?php foreach ($series as $serie): ?>

            <article class="carte">

                <?php if (!empty($serie["vignette"])): ?>
                    <img class="image-carte"
                         src="<?= e($serie["vignette"]) ?>"
                         alt="<?= e($serie["nom"]) ?>">
                <?php endif; ?>

                <div class="contenu-carte">

                    <h2><?= e($serie["nom"]) ?></h2>

                    <p class="date">
                        Sortie : <?= date("d/m/Y", strtotime($serie["date_sortie"])) ?>
                    </p>

                    <?php if (!empty($serie["resume"])): ?>
                        <p class="resume">
                            <?= nl2br(e($serie["resume"])) ?>
                        </p>
                    <?php endif; ?>

                    <a class="bouton bouton-secondaire"
                       href="serie.php?id=<?= $serie["id"] ?>">
                        Voir la série
                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <div class="vide">
        <p>Aucune série n'a encore été ajoutée.</p>
    </div>

<?php endif; ?>

<?php require "../includes/footer.php"; ?>