<?php
require_once __DIR__ . '/../config/base_de_donnees.php';

$titrePage = 'Mes séries';
$cheminRacine = '';

// On récupère toutes les séries, les plus récemment sorties en premier.
$stmt = $pdo->query('SELECT * FROM SERIE ORDER BY date_sortie DESC');
$series = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../commun/en_tete.php';
?>

<h1>Mes séries</h1>

<?php if (empty($series)): ?>
    <p class="message-vide">Aucune série ajoutée pour le moment.</p>
    <a href="series/ajouter.php" class="btn-principal btn-lien">Ajouter une série</a>
<?php else: ?>
    <div class="grille-cartes">
        <?php foreach ($series as $serie): ?>
            <a href="series/detail.php?id=<?= (int) $serie['id'] ?>" class="carte">
                <?php if (!empty($serie['vignette'])): ?>
                    <img src="<?= htmlspecialchars($serie['vignette']) ?>" alt="Vignette de <?= htmlspecialchars($serie['nom']) ?>" class="carte-vignette">
                <?php else: ?>
                    <div class="carte-vignette carte-vignette-vide">Pas d'image</div>
                <?php endif; ?>
                <div class="carte-contenu">
                    <h2><?= htmlspecialchars($serie['nom']) ?></h2>
                    <p class="carte-date">Sortie le <?= date('d/m/Y', strtotime($serie['date_sortie'])) ?></p>
                    <?php if (!empty($serie['resume'])): ?>
                        <p class="carte-resume"><?= htmlspecialchars(mb_strimwidth($serie['resume'], 0, 120, '…')) ?></p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../commun/pied_de_page.php'; ?>
