<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

$titrePage = "Mes séries";

$requete = $pdo->query("SELECT * FROM serie ORDER BY date_sortie DESC");
$series = $requete->fetchAll(PDO::FETCH_ASSOC);

require "../includes/header.php";
?>

<section class="banniere">

    <div class="contenu-banniere">

        <p class="petit-titre">MY SERIES COMPANION</p>

        <h1>Toutes tes séries au même endroit.</h1>

        <p>
            Ajoute tes séries, leurs saisons et leurs épisodes,
            puis retrouve-les facilement.
        </p>

        <a class="bouton" href="ajouter-serie.php">
            + Ajouter une série
        </a>

    </div>

</section>

<div class="titre-section">

    <div>
        <h2>Mes séries</h2>
        <p class="date">
            <?= count($series) ?> série(s) enregistrée(s)
        </p>
    </div>

</div>

<?php if (count($series) > 0): ?>

    <div class="grille-series">

        <?php foreach ($series as $serie): ?>

            <article class="carte">

                <?php if (!empty($serie["vignette"])): ?>

                    <img
                        class="image-carte"
                        src="<?= e($serie["vignette"]) ?>"
                        alt="<?= e($serie["nom"]) ?>"
                    >

                <?php endif; ?>

                <div class="contenu-carte">

                    <h2><?= e($serie["nom"]) ?></h2>

                    <p class="date">
                        Sortie :
                        <?= date("d/m/Y", strtotime($serie["date_sortie"])) ?>
                    </p>

                    <?php if (!empty($serie["resume"])): ?>

                        <p class="resume">
                            <?= nl2br(e($serie["resume"])) ?>
                        </p>

                    <?php endif; ?>

                    <a
                        class="bouton bouton-secondaire"
                        href="serie.php?id=<?= $serie["id"] ?>"
                    >
                        Voir la série
                    </a>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <div class="vide">
        <p>
            Aucune série n'a encore été ajoutée.
        </p>

        <a class="bouton" href="ajouter-serie.php">
            Ajouter ma première série
        </a>
    </div>

<?php endif; ?>

<?php require "../includes/footer.php"; ?>