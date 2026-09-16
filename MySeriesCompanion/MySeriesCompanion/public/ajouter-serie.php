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
        // Ajout de la série
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

        // Fait à l'aide de l'IA
        // Ajout automatique des saisons et épisodes

        $cleTmdb = "f63049092c6fa9160155c2248faeb2e6";

        // Recherche de la série
        $urlSerie = "https://api.themoviedb.org/3/search/tv?api_key=" . $cleTmdb . "&language=fr-FR&query=" . urlencode($nom);
        $reponseSerie = file_get_contents($urlSerie);

        if ($reponseSerie !== false) {
            $resultatSerie = json_decode($reponseSerie, true);

            if (!empty($resultatSerie["results"])) {
                $serieApi = $resultatSerie["results"][0];
                $idApi = $serieApi["id"];

                // Récupération des informations de la série
                $urlDetails = "https://api.themoviedb.org/3/tv/" . $idApi . "?api_key=" . $cleTmdb . "&language=fr-FR";
                $reponseDetails = file_get_contents($urlDetails);

                if ($reponseDetails !== false) {
                    $detailsSerie = json_decode($reponseDetails, true);

                    if (!empty($detailsSerie["seasons"])) {
                        foreach ($detailsSerie["seasons"] as $saisonApi) {
                            $numeroSaison = $saisonApi["season_number"];

                            // On ne prend pas la saison 0
                            if ($numeroSaison > 0) {
                                $nomSaison = "Saison " . $numeroSaison;
                                $resumeSaison = $saisonApi["overview"] ?? "";
                                $vignetteSaison = "";

                                if (!empty($saisonApi["poster_path"])) {
                                    $vignetteSaison = "https://image.tmdb.org/t/p/w500" . $saisonApi["poster_path"];
                                }

                                $dateSaison = $dateSortie;

                                if (!empty($saisonApi["air_date"])) {
                                    $dateSaison = $saisonApi["air_date"];
                                }

                                // Ajout de la saison
                                $sql = "INSERT INTO saison
                                        (nom, resume, vignette, date_sortie, serie_id)
                                        VALUES
                                        (:nom, :resume, :vignette, :date_sortie, :serie_id)";

                                $requete = $pdo->prepare($sql);
                                $requete->execute([
                                    "nom" => $nomSaison,
                                    "resume" => $resumeSaison,
                                    "vignette" => $vignetteSaison,
                                    "date_sortie" => $dateSaison,
                                    "serie_id" => $idSerie
                                ]);

                                $idSaison = $pdo->lastInsertId();

                                // Récupération des épisodes
                                $urlSaison = "https://api.themoviedb.org/3/tv/" . $idApi . "/season/" . $numeroSaison . "?api_key=" . $cleTmdb . "&language=fr-FR";
                                $reponseSaison = file_get_contents($urlSaison);

                                if ($reponseSaison !== false) {
                                    $detailsSaison = json_decode($reponseSaison, true);

                                    if (!empty($detailsSaison["episodes"])) {
                                        foreach ($detailsSaison["episodes"] as $episodeApi) {
                                            $nomEpisode = $episodeApi["name"];
                                            $resumeEpisode = $episodeApi["overview"] ?? "";
                                            $vignetteEpisode = "";

                                            if (!empty($episodeApi["still_path"])) {
                                                $vignetteEpisode = "https://image.tmdb.org/t/p/w500" . $episodeApi["still_path"];
                                            }

                                            $dateEpisode = $dateSaison;

                                            if (!empty($episodeApi["air_date"])) {
                                                $dateEpisode = $episodeApi["air_date"];
                                            }

                                            $dureeEpisode = null;

                                            if (!empty($episodeApi["runtime"])) {
                                                $dureeEpisode = $episodeApi["runtime"];
                                            }

                                            // Ajout de l'épisode
                                            $sql = "INSERT INTO episode
                                                    (nom, resume, vignette, date_sortie, duree, saison_id)
                                                    VALUES
                                                    (:nom, :resume, :vignette, :date_sortie, :duree, :saison_id)";

                                            $requete = $pdo->prepare($sql);
                                            $requete->execute([
                                                "nom" => $nomEpisode,
                                                "resume" => $resumeEpisode,
                                                "vignette" => $vignetteEpisode,
                                                "date_sortie" => $dateEpisode,
                                                "duree" => $dureeEpisode,
                                                "saison_id" => $idSaison
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        header("Location: serie.php?id=" . $idSerie);
        exit;
    }
}

require "../includes/header.php";
?>

<section class="titre-page">
    <h1>Ajouter une série</h1>
    <p>Ajoute une nouvelle série à ton espace. Les saisons et épisodes seront ajoutés automatiquement.</p>
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
        <label for="nom">Nom <span class="obligatoire">*</span></label>
        <input type="text" id="nom" name="nom" required value="<?= e($_POST["nom"] ?? "") ?>">
    </div>

    <div class="groupe-formulaire">
        <label for="resume">Résumé</label>
        <textarea id="resume" name="resume"><?= e($_POST["resume"] ?? "") ?></textarea>
    </div>

    <div class="groupe-formulaire">
        <label for="vignette">URL de la vignette</label>
        <input type="url" id="vignette" name="vignette" placeholder="https://exemple.com/photo.jpg" value="<?= e($_POST["vignette"] ?? "") ?>">
    </div>

    <div class="groupe-formulaire">
        <label for="date_sortie">Date de sortie <span class="obligatoire">*</span></label>
        <input type="date" id="date_sortie" name="date_sortie" required value="<?= e($_POST["date_sortie"] ?? "") ?>">
    </div>

    <button class="bouton" type="submit">Ajouter la série</button>
    <a class="bouton bouton-secondaire" href="index.php">Annuler</a>
</form>

<?php require "../includes/footer.php"; ?>