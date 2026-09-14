<?php
// Connexion à la base de données et inclusion des fonctions
require_once "../config/database.php";
require_once "../includes/functions.php";

$titrePage = "Mes séries";

// Récupération des séries depuis la base de données
$requete = $pdo->query("SELECT * FROM serie ORDER BY date_sortie DESC");
$series = $requete->fetchAll(PDO::FETCH_ASSOC);

require "../includes/header.php";
?>

<section class="page-title">
    <h1>Mes séries</h1>
    <p>Cette application te permet de gérer tes séries, leurs saisons et leurs épisodes.</p>
</section>

<!-- Bouton pour ajouter une nouvelle série -->
<div class="home-action">
    <a class="btn" href="ajouter-serie.php">+ Ajouter une série</a>
</div>

<?php if (count($series) > 0): ?>

    <!-- Affichage de toutes les séries -->
    <div class="series-grid">

        <?php foreach ($series as $serie): ?>

            <article class="card">

                <?php if (!empty($serie["vignette"])): ?>

                    <!-- Affichage de la vignette avec son URL -->
                    <img
                        class="vignette-carte"
                        src="<?= e($serie["vignette"]) ?>"
                        alt="Vignette de <?= e($serie["nom"]) ?>"
                    >

                <?php endif; ?>

                <div class="contenant-carte">

                    <h2><?= e($serie["nom"]) ?></h2>

                    <p class="date">
                        Sortie : <?= date("d/m/Y", strtotime($serie["date_sortie"])) ?>
                    </p>

                    <?php if (!empty($serie["resume"])): ?>
                        <p class="resume"><?= nl2br(e($serie["resume"])) ?></p>
                    <?php endif; ?>

                    <a class="btn btn-secondary" href="serie.php?id=<?= $serie["id"] ?>">
                        Voir la série
                    </a>

                </div>
            </article>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <!-- Message affiché lorsqu'aucune série n'existe -->
    <div class="vide">
        <p>Aucune série n'a encore été ajoutée.</p>
    </div>

<?php endif; ?>

<?php require "../includes/footer.php"; ?>