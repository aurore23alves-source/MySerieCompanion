<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

$titrePage = "Ajouter une série";
$erreurs = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");
    $resume = trim($_POST["resume"] ?? "");
    $vignette = trim($_POST["vignette"] ?? "");
    $dateSortie = $_POST["date_sortie"] ?? "";

    if ($nom === "") {
        $erreurs[] = "Le nom de la série est obligatoire.";
    }

    if ($dateSortie === "") {
        $erreurs[] = "La date de sortie est obligatoire.";
    }

    if (empty($erreurs)) {

        $sql = "INSERT INTO serie
                (nom, resume, vignette, date_sortie)
                VALUES (:nom, :resume, :vignette, :date_sortie)";

        $requete = $pdo->prepare($sql);

        $requete->execute([
            "nom" => $nom,
            "resume" => $resume,
            "vignette" => $vignette,
            "date_sortie" => $dateSortie
        ]);

        $idSerie = $pdo->lastInsertId();

        header("Location: serie.php?id=" . $idSerie);
        exit;
    }
}

require "../includes/header.php";
?>

<section class="titre-page">
    <h1>Ajouter une série</h1>
    <p>Ajoute une nouvelle série à ton espace.</p>
</section>

<?php if (!empty($erreurs)): ?>

    <div class="erreur">
        <?php foreach ($erreurs as $erreur): ?>
            <p><?= e($erreur) ?></p>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<form class="carte-formulaire" method="POST">

    <div class="groupe-formulaire">
        <label for="nom">
            Nom <span class="obligatoire">*</span>
        </label>

        <input type="text"
               id="nom"
               name="nom"
               required
               value="<?= e($_POST["nom"] ?? "") ?>">
    </div>

    <div class="groupe-formulaire">
        <label for="resume">Résumé</label>

        <textarea id="resume"
                  name="resume"><?= e($_POST["resume"] ?? "") ?></textarea>
    </div>

    <div class="groupe-formulaire">
        <label for="vignette">URL de la vignette</label>

        <input type="url"
               id="vignette"
               name="vignette"
               placeholder="https://exemple.com/photo.jpg"
               value="<?= e($_POST["vignette"] ?? "") ?>">
    </div>

    <div class="groupe-formulaire">
        <label for="date_sortie">
            Date de sortie <span class="obligatoire">*</span>
        </label>

        <input type="date"
               id="date_sortie"
               name="date_sortie"
               required
               value="<?= e($_POST["date_sortie"] ?? "") ?>">
    </div>

    <button class="bouton" type="submit">
        Ajouter la série
    </button>

    <a class="bouton bouton-secondaire" href="index.php">
        Annuler
    </a>

</form>

<?php require "../includes/footer.php"; ?>