<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

$titrePage = "Mes séries";

$requete = $pdo->query("SELECT * FROM serie ORDER BY date_sortie DESC");
$series = $requete->fetchAll(PDO::FETCH_ASSOC);

require "../includes/header.php";
?>

<section class="page-title">
    <h1>Mes séries</h1>
    <p>Cette application te permet de gérer tes séries, leurs saisons et leurs épisodes.</p>
</section>

<div class="home-action">
    <a class="btn" href="ajouter-serie.php">+ Ajouter une série</a>
</div>


