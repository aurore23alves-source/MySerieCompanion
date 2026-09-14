<?php

/**
 * Protège l'affichage d'un texte dans le HTML.
 */
function e($texte) {
    return htmlspecialchars($texte ?? "", ENT_QUOTES, "UTF-8");
}

/**
 * Vérifie qu'un fichier envoyé est une image.
 * Retourne le nouveau nom du fichier ou une chaîne vide.
 */
function enregistrerImage($fichier) {
    if (!isset($fichier) || $fichier["error"] === UPLOAD_ERR_NO_FILE) {
        return "";
    }

    if ($fichier["error"] !== UPLOAD_ERR_OK) {
        return "";
    }

    $extensionsAutorisees = ["jpg", "jpeg", "png", "webp"];
    $extension = strtolower(pathinfo($fichier["name"], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionsAutorisees)) {
        return "";
    }

    // Création d'un nom simple et unique.
    $nouveauNom = uniqid("image_") . "." . $extension;
    $destination = __DIR__ . "/../public/uploads/" . $nouveauNom;

    if (move_uploaded_file($fichier["tmp_name"], $destination)) {
        return $nouveauNom;
    }

    return "";
}

/**
 * Vérifie une date saisie dans un formulaire.
 */
function dateValide($date) {
    $dateObjet = DateTime::createFromFormat("Y-m-d", $date);
    return $dateObjet && $dateObjet->format("Y-m-d") === $date;
}

?>
