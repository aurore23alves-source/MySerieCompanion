<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

// Récupération de l'id de la saison
if (isset($_GET["saison_id"])) {
    $saisonId = (int) $_GET["saison_id"];
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

$titrePage = "Ajouter un épisode";
$erreurs = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");
    $resume = trim($_POST["resume"] ?? "");
    $vignette = trim($_POST["vignette"] ?? "");
    $dateSortie = $_POST["date_sortie"] ?? "";
    $duree = $_POST["duree"] ?? "";

    if ($nom === "") {
        $erreurs[] = "Le nom de l'épisode est obligatoire.";
    }

    if ($dateSortie === "") {
        $erreurs[] = "La date de sortie est obligatoire.";
    }

    if ($duree !== "" && $duree <= 0) {
        $erreurs[] = "La durée doit être supérieure à 0.";
    }

    if (empty($erreurs)) {

        $sql = "INSERT INTO episode
                (nom, resume, vignette, date_sortie, duree, saison_id)
                VALUES (:nom, :resume, :vignette, :date_sortie, :duree, :saison_id)";

        $requete = $pdo->prepare($sql);

        $requete->execute([
            "nom" => $nom,
            "resume" => $resume,
            "vignette" => $vignette,
            "date_sortie" => $dateSortie,
            "duree" => $duree !== "" ? $duree : null,
            "saison_id" => $saisonId
        ]);

        header("Location: saison.php?id=" . $saisonId);
        exit;
    }
}

require "../includes/header.php";
?>

<section class="titre-page">
    <h1>Ajouter un épisode</h1>

    <p>
        <?= e($saison["nom_serie"]) ?> →
        <strong><?= e($saison["nom"]) ?></strong>
    </p>
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

    <div class="groupe-formulaire">
        <label for="duree">Durée en minutes</label>

        <input type="number"
               id="duree"
               name="duree"
               min="1"
               value="<?= e($_POST["duree"] ?? "") ?>">
    </div>

    <button class="bouton" type="submit">
        Ajouter l'épisode
    </button>

    <a class="bouton bouton-secondaire"
       href="saison.php?id=<?= $saisonId ?>">
        Annuler
    </a>

</form>

<?php require "../includes/footer.php"; ?>