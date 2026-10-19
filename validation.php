<?php
require_once 'config.php';
exigerAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $pdo->prepare('UPDATE event SET is_validated = 1 WHERE id = :id')
        ->execute(['id' => (int) $_POST['id']]);

    flash('success', "L'événement a bien été validé.");
    redirect('validation.php');
}

$events = $pdo->query('SELECT * FROM event WHERE is_validated = 0 ORDER BY date ASC')->fetchAll();
$nb     = count($events);

$pageTitle = 'Validation des événements';
require 'header.php';
?>

    <div class="section-head">
        <div>
            <h1>Événements à valider</h1>
            <p class="muted"><?= $nb ?> événement<?= $nb > 1 ? 's' : '' ?> en attente de validation.</p>
        </div>
    </div>

    <?php if ($nb === 0) { ?>

        <p class="empty">Aucun événement en attente 🎉</p>

    <?php } else { ?>

        <div class="card table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Événement</th>
                        <th>Date</th>
                        <th>Catégorie</th>
                        <th>Places</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) { ?>
                        <tr>
                            <td><a href="evenement.php?id=<?= (int) $event['id'] ?>"><?= h($event['title']) ?></a></td>
                            <td><?= dateFr($event['date'], 'd/m/Y H\hi') ?></td>
                            <td>
                                <span class="badge cat-<?= h($event['category']) ?>">
                                    <?= h(CATEGORIES[$event['category']] ?? $event['category']) ?>
                                </span>
                            </td>
                            <td><?= (int) $event['places'] ?></td>
                            <td><span class="badge badge-pending">Non validé</span></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="id" value="<?= (int) $event['id'] ?>">
                                    <button type="submit" class="btn btn-small btn-success">Valider</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    <?php } ?>

<?php require 'footer.php'; ?>
