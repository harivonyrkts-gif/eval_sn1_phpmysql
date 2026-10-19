<?php
require 'config.php';

$categorie = $_GET['categorie'] ?? null;
if (!in_array($categorie, array_keys(CATEGORIES), true)) {
    $categorie = null;
}

$sql    = 'SELECT * FROM event WHERE is_validated = 1 AND date > NOW()';
$params = [];

if ($categorie !== null) {
    $sql .= ' AND category = :categorie';
    $params['categorie'] = $categorie;
}

$sth = $pdo->prepare($sql . ' ORDER BY date ASC');
$sth->execute($params);
$events = $sth->fetchAll();

$mois = [1 => 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];

$pageTitle = 'Événements à venir';
require 'header.php';
?>

    <section class="hero" style="background-image: url('images/rock.jpg')">
        <div class="hero-content">
            <span class="hero-kicker">Agenda local</span>
            <h1>Les événements près de chez vous</h1>
            <p>Concerts, conférences, ateliers et sport : trouvez votre prochaine sortie ou proposez la vôtre.</p>
            <a class="btn" href="ajouter.php">+ Ajouter un événement</a>
        </div>
    </section>

    <div class="section-head">
        <h2>À venir</h2>
        <ul class="legend">
            <li><a class="<?= $categorie === null ? 'active' : '' ?>" href="index.php">Toutes</a></li>
            <?php foreach (CATEGORIES as $cle => $libelle) { ?>
                <li class="cat-<?= $cle ?>">
                    <a class="<?= $categorie === $cle ? 'active' : '' ?>" href="index.php?categorie=<?= $cle ?>">
                        <?= $libelle ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>

    <?php if (!$events) { ?>

        <p class="empty">Aucun événement à venir pour le moment.</p>

    <?php } else { ?>

        <ul class="event-list">
            <?php foreach ($events as $event) {
                $timestamp = strtotime($event['date']);
            ?>
                <li class="event-card cat-<?= h($event['category']) ?>">
                    <div class="event-media">
                        <?php if (!empty($event['image'])) { ?>
                            <img class="event-image" src="<?= h($event['image']) ?>" alt="">
                        <?php } ?>
                        <div class="event-date">
                            <span class="day"><?= date('d', $timestamp) ?></span>
                            <span class="month"><?= $mois[date('n', $timestamp)] ?></span>
                        </div>
                        <span class="badge"><?= h(CATEGORIES[$event['category']] ?? $event['category']) ?></span>
                    </div>
                    <h3 class="event-title">
                        <a href="evenement.php?id=<?= (int) $event['id'] ?>"><?= h($event['title']) ?></a>
                    </h3>
                    <span class="muted"><?= dateFr($event['date']) ?></span>
                </li>
            <?php } ?>
        </ul>

    <?php } ?>

<?php require 'footer.php'; ?>
