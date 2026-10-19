<?php
require_once 'config.php';
exigerAdmin();

$events = $pdo->query('SELECT * FROM event WHERE is_validated = 1 ORDER BY date ASC')->fetchAll();

$sthInscrits = $pdo->prepare('
    SELECT user.email, reservation.created_at
    FROM reservation
    INNER JOIN user ON user.id = reservation.user_id
    WHERE reservation.event_id = :event
    ORDER BY reservation.created_at ASC
');

$pageTitle = 'Inscrits aux événements';
require 'header.php';
?>

    <div class="section-head">
        <div>
            <h1>Inscrits aux événements</h1>
            <p class="muted">Liste des participants pour chaque événement validé.</p>
        </div>
    </div>

    <?php if (!$events) { ?>

        <p class="empty">Aucun événement validé pour le moment.</p>

    <?php } ?>

    <?php foreach ($events as $event) {
        $sthInscrits->execute(['event' => $event['id']]);
        $inscrits   = $sthInscrits->fetchAll();
        $nbInscrits = count($inscrits);
    ?>

        <section class="card attendees">
            <div class="attendees-head">
                <div>
                    <h2><a href="evenement.php?id=<?= (int) $event['id'] ?>"><?= h($event['title']) ?></a></h2>
                    <span class="badge cat-<?= h($event['category']) ?>">
                        <?= h(CATEGORIES[$event['category']] ?? $event['category']) ?>
                    </span>
                    <span class="muted"><?= dateFr($event['date']) ?></span>
                </div>
                <p class="places">
                    <?php if ($nbInscrits >= $event['places']) { ?>
                        <span class="badge badge-full">Complet</span>
                    <?php } ?>
                    <strong><?= $nbInscrits ?></strong> inscrit<?= $nbInscrits > 1 ? 's' : '' ?> sur <?= (int) $event['places'] ?> places
                </p>
            </div>

            <?php if ($nbInscrits === 0) { ?>

                <p class="empty">Aucun inscrit pour le moment.</p>

            <?php } else { ?>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Inscrit le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscrits as $inscrit) { ?>
                                <tr>
                                    <td><?= h($inscrit['email']) ?></td>
                                    <td><?= dateFr($inscrit['created_at'], 'd/m/Y H\hi') ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            <?php } ?>
        </section>

    <?php } ?>

<?php require 'footer.php'; ?>
