<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);

$sql = 'SELECT * FROM event WHERE id = :id';
if (!estAdmin()) {
    $sql .= ' AND is_validated = 1';
}
$sth = $pdo->prepare($sql);
$sth->execute(['id' => $id]);
$event = $sth->fetch();

if (!$event) {
    http_response_code(404);
    require '404.php';
    exit;
}

$userId      = $_SESSION['user']['id'] ?? null;
$estConnecte = $userId !== null;

$sth = $pdo->prepare('SELECT COUNT(*) FROM reservation WHERE event_id = :event');
$sth->execute(['event' => $id]);
$nbInscrits = (int) $sth->fetchColumn();

$placesRestantes = $event['places'] - $nbInscrits;
$complet         = $placesRestantes <= 0;

$dejaInscrit = false;
if ($estConnecte) {
    $sth = $pdo->prepare('SELECT COUNT(*) FROM reservation WHERE user_id = :user AND event_id = :event');
    $sth->execute(['user' => $userId, 'event' => $id]);
    $dejaInscrit = $sth->fetchColumn() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $estConnecte) {
    if (isset($_POST['inscrire'])) {
        if ($dejaInscrit) {
            flash('error', 'Vous êtes déjà inscrit à cet événement.');
        } elseif ($complet) {
            flash('error', 'Cet événement est complet.');
        } else {
            $sth = $pdo->prepare('INSERT INTO reservation (user_id, event_id) VALUES (:user, :event)');
            $sth->execute(['user' => $userId, 'event' => $id]);
            flash('success', 'Votre inscription est enregistrée.');
        }
    } elseif (isset($_POST['desinscrire'])) {
        $sth = $pdo->prepare('DELETE FROM reservation WHERE user_id = :user AND event_id = :event');
        $sth->execute(['user' => $userId, 'event' => $id]);
        flash('success', 'Vous êtes désinscrit de cet événement.');
    }

    redirect('evenement.php?id=' . $id);
}

$jours     = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$dateTexte = $jours[date('w', strtotime($event['date']))] . ' ' . dateFr($event['date']);

$pourcentage = $event['places'] > 0 ? min(100, round($nbInscrits / $event['places'] * 100)) : 100;

$pageTitle = $event['title'];
require 'header.php';
?>

    <a class="back-link" href="index.php">← Retour aux événements</a>

    <article class="cat-<?= h($event['category']) ?>">
        <header class="detail-hero"<?php if (!empty($event['image'])) { ?> style="background-image: url('<?= h($event['image']) ?>')"<?php } ?>>
            <div class="detail-hero-content">
                <span class="badge"><?= h(CATEGORIES[$event['category']] ?? $event['category']) ?></span>
                <h1><?= h($event['title']) ?></h1>
                <p><?= $dateTexte ?></p>
            </div>
        </header>

        <div class="detail-grid">
            <section class="card">
                <h2>Informations</h2>
                <p><?= nl2br(h($event['information'])) ?></p>
            </section>

            <aside class="card booking">

                <?php if ($complet) { ?>
                    <p class="places"><span class="badge badge-full">Complet</span></p>
                <?php } else { ?>
                    <p class="places"><strong><?= $placesRestantes ?></strong> places restantes sur <?= (int) $event['places'] ?></p>
                <?php } ?>

                <div class="progress"><span style="width: <?= $pourcentage ?>%"></span></div>

                <?php if (!$estConnecte) { ?>

                    <a class="btn btn-block" href="connexion.php">Connectez-vous pour vous inscrire</a>

                <?php } elseif ($dejaInscrit) { ?>

                    <p class="muted">Vous êtes inscrit à cet événement.</p>
                    <form method="post">
                        <button type="submit" name="desinscrire" class="btn btn-secondary btn-block">Se désinscrire</button>
                    </form>

                <?php } elseif ($complet) { ?>

                    <button class="btn btn-block" disabled>Complet</button>

                <?php } else { ?>

                    <form method="post">
                        <button type="submit" name="inscrire" class="btn btn-block">S'inscrire</button>
                    </form>

                <?php } ?>

            </aside>
        </div>
    </article>

<?php require 'footer.php'; ?>
