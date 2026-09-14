<?php
if (!isset($titrePage)) {
    $titrePage = "My Series Companion";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titrePage) ?> - My Series Companion</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a class="logo" href="index.php">My Series Companion</a>

        <nav>
            <a href="index.php">Mes séries</a>
            <a class="btn btn-small" href="ajouter-serie.php">+ Ajouter une série</a>
        </nav>
    </div>
</header>

<main class="container">
