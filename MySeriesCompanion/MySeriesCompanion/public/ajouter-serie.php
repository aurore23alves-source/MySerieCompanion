<?php
// Connexion à la base de données  et fonctions
require_once "../config/database.php";
require_once "../includes/functions.php";

$titrePage = "Ajouter une série";
$erreurs = [];

// Vérification du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Données du formulaire
    $nom = trim($_POST["nom"] ?? "");
    $resume = trim($_POST["resume"] ?? "");
    $dateSortie = $_POST["date_sortie"] ?? "";

    // Champs obligatoires
    if ($nom === "") {
        $erreurs[] = "Le nom de la série est obligatoire.";
    }

    if ($dateSortie === "" || !dateValide($dateSortie)) {
        $erreurs[] = "La date de sortie est obligatoire et doit être valide.";
    }

    $vignette = trim($_POST["vignette"] ?? ""); 

    // On enregistre la série
    if (empty($erreurs)) {
        $sql = "INSERT INTO serie (nom, resume, vignette, date_sortie)
                VALUES (:nom, :resume, :vignette, :date_sortie)";

        $requete = $pdo->prepare($sql);
        $requete->execute([
            "nom" => $nom,
            "resume" => $resume !== "" ? $resume : null,
            "vignette" => $vignette !== "" ? $vignette : null,
            "date_sortie" => $dateSortie
        ]);

        // Récupère l'ID de la série créée
        $idSerie = $pdo->lastInsertId();

        // Redirige vers la page de la série
        header("Location: serie.php?id=" . $idSerie);
        exit;
    }
}

require "../includes/header.php";
?>

<section class="page-title">
    <h1>Ajouter une série</h1>
    <p>Ajoute une nouvelle série à ton espace.</p>
</section>

<?php if (!empty($erreurs)): ?>
    
    <div class="error">
        <?php foreach ($erreurs as $erreur): ?>
            <p><?= e($erreur) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Formulaire d'ajout -->

<form class="form-card" method="POST">

    <div class="form-group">
        <label for="nom">Nom <span class="required"></span></label>
        <input type="text" id="nom" name="nom" required
        value="<?= e($_POST["nom"] ?? "") ?>">
    </div>

    <div class="form-group">
        <label for="resume">Résumé</label>
        <textarea id="resume" name="resume"><?= e($_POST["resume"] ?? "") ?></textarea>
    </div>

    <div class="form-group">
    <label for="vignette">URL de la vignette</label>
    <input type="url" id="vignette" name="vignette"
           placeholder="https://exemple.com/photo.jpg"
           value="<?= e($_POST["vignette"] ?? "") ?>">
</div>

    <div class="form-group">
        <label for="date_sortie">Date de sortie <span class="required"></span></label>
        <input type="date" id="date_sortie" name="date_sortie" required
                value="<?= e($_POST["date_sortie"] ?? "") ?>">
    </div>

    <button class="btn" type="submit">Ajouter la série</button>
    <a class="btn btn-secondary" href="index.php">Annuler</a>
</form>

<?php require "../includes/footer.php"; ?>