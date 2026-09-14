<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

// Récupération de l'id de la série
if (isset($_GET["serie_id"])) {
    $serieId = (int) $_GET["serie_id"];
} else {
    header("Location: index.php");
    exit;
}

// Récupération de la série
$requete = $pdo->prepare("SELECT * FROM serie WHERE id = :id");
$requete->execute([
    "id" => $serieId
]);

$serie = $requete->fetch(PDO::FETCH_ASSOC);

if (!$serie) {
    header("Location: index.php");
    exit;
}

$titrePage = "Ajouter une saison";
$erreurs = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");
    $resume = trim($_POST["resume"] ?? "");
    $vignette = trim($_POST["vignette"] ?? "");
    $dateSortie = $_POST["date_sortie"] ?? "";

    if ($nom === "") {
        $erreurs[] = "Le nom de la saison est obligatoire.";
    }

    if ($dateSortie === "") {
        $erreurs[] = "La date de sortie est obligatoire.";
    }

    if (empty($erreurs)) {

        $sql = "INSERT INTO saison
                (nom, resume, vignette, date_sortie, serie_id)
                VALUES (:nom, :resume, :vignette, :date_sortie, :serie_id)";

        $requete = $pdo->prepare($sql);

        $requete->execute([
            "nom" => $nom,
            "resume" => $resume,
            "vignette" => $vignette,
            "date_sortie" => $dateSortie,
            "serie_id" => $serieId
        ]);

        header("Location: serie.php?id=" . $serieId);
        exit;
    }
}

require "../includes/header.php";
?>

<section class="titre-page">

    <h1>Ajouter une saison</h1>

    <p>
        Série :
        <strong><?= e($serie["nom"]) ?></strong>
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

    <button class="bouton" type="submit">
        Ajouter la saison
    </button>

    <a class="bouton bouton-secondaire"
       href="serie.php?id=<?= $serieId ?>">
        Annuler
    </a>

</form>

<?php require "../includes/footer.php"; ?>