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

        // Recherche de la série avec son nom
        $urlSerie = "https://api.tvmaze.com/singlesearch/shows?q="
                    . urlencode($nom);

        $reponseSerie = file_get_contents($urlSerie);

        if ($reponseSerie !== false) {

            $serieApi = json_decode($reponseSerie, true);

            if (!empty($serieApi["id"])) {

                $idApi = $serieApi["id"];

                // Récupération des saisons
                $urlSaisons = "https://api.tvmaze.com/shows/"
                              . $idApi
                              . "/seasons";

                $reponseSaisons = file_get_contents($urlSaisons);

                if ($reponseSaisons !== false) {

                    $saisonsApi = json_decode($reponseSaisons, true);

                    foreach ($saisonsApi as $saisonApi) {

                        $numeroSaison = $saisonApi["number"];

                        $nomSaison = "Saison " . $numeroSaison;

                        $resumeSaison = "";

                        if (!empty($saisonApi["summary"])) {
                            $resumeSaison =
                                strip_tags($saisonApi["summary"]);
                        }

                        $vignetteSaison = "";

                        if (!empty($saisonApi["image"]["original"])) {
                            $vignetteSaison =
                                $saisonApi["image"]["original"];
                        }

                        $dateSaison = $dateSortie;

                        if (!empty($saisonApi["premiereDate"])) {
                            $dateSaison =
                                $saisonApi["premiereDate"];
                        }


                        // Ajout de la saison dans MySQL
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


                        // Récupération des épisodes de la saison
                        $urlEpisodes = "https://api.tvmaze.com/seasons/"
                                       . $saisonApi["id"]
                                       . "/episodes";

                        $reponseEpisodes =
                            file_get_contents($urlEpisodes);

                        if ($reponseEpisodes !== false) {

                            $episodesApi =
                                json_decode($reponseEpisodes, true);

                            foreach ($episodesApi as $episodeApi) {

                                $nomEpisode = $episodeApi["name"];

                                $resumeEpisode = "";

                                if (!empty($episodeApi["summary"])) {
                                    $resumeEpisode =
                                        strip_tags($episodeApi["summary"]);
                                }

                                $vignetteEpisode = "";

                                if (!empty($episodeApi["image"]["original"])) {
                                    $vignetteEpisode =
                                        $episodeApi["image"]["original"];
                                }

                                $dateEpisode = $dateSaison;

                                if (!empty($episodeApi["airdate"])) {
                                    $dateEpisode =
                                        $episodeApi["airdate"];
                                }

                                $dureeEpisode = null;

                                if (!empty($episodeApi["runtime"])) {
                                    $dureeEpisode =
                                        $episodeApi["runtime"];
                                }


                                // Ajout de l'épisode dans MySQL
                                $sql = "INSERT INTO episode
                                        (nom, resume, vignette,
                                         date_sortie, duree, saison_id)
                                        VALUES
                                        (:nom, :resume, :vignette,
                                         :date_sortie, :duree, :saison_id)";

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


        header("Location: serie.php?id=" . $idSerie);
        exit;
    }
}

require "../includes/header.php";
?>

<section class="titre-page">

    <h1>Ajouter une série</h1>

    <p>
        Ajoute une nouvelle série à ton espace.
        Les saisons et épisodes seront ajoutés automatiquement.
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

        <input
            type="text"
            id="nom"
            name="nom"
            required
            value="<?= e($_POST["nom"] ?? "") ?>"
        >

    </div>


    <div class="groupe-formulaire">

        <label for="resume">
            Résumé
        </label>

        <textarea
            id="resume"
            name="resume"
        ><?= e($_POST["resume"] ?? "") ?></textarea>

    </div>


    <div class="groupe-formulaire">

        <label for="vignette">
            URL de la vignette
        </label>

        <input
            type="url"
            id="vignette"
            name="vignette"
            placeholder="https://exemple.com/photo.jpg"
            value="<?= e($_POST["vignette"] ?? "") ?>"
        >

    </div>


    <div class="groupe-formulaire">

        <label for="date_sortie">
            Date de sortie
            <span class="obligatoire">*</span>
        </label>

        <input
            type="date"
            id="date_sortie"
            name="date_sortie"
            required
            value="<?= e($_POST["date_sortie"] ?? "") ?>"
        >

    </div>


    <button class="bouton" type="submit">
        Ajouter la série
    </button>

    <a
        class="bouton bouton-secondaire"
        href="index.php"
    >
        Annuler
    </a>

</form>

<?php require "../includes/footer.php"; ?>