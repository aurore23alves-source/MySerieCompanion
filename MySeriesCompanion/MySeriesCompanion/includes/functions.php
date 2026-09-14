<?php

// Sécurise l'affichage du texte
function e($texte) {
    return htmlspecialchars($texte ?? "", ENT_QUOTES, "UTF-8");
}

?>