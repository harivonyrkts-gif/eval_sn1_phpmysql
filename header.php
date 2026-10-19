<?php
// Raccourcis utilisés dans le menu
$estConnecte = isset($_SESSION['user']);
$estAdmin    = $estConnecte && $_SESSION['user']['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Agenda') ?> · Agenda</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 128 128%22><text y=%221.2em%22 font-size=%2296%22>📅</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a class="logo" href="index.php">Agenda<span>.</span></a>
            <nav class="nav">
                <a href="index.php">Événements</a>

                <?php if ($estAdmin) { ?>
                    <a href="validation.php">
                        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                        Validation
                    </a>
                    <a href="inscrits.php">
                        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="4"/><path d="M2 21v-1a6 6 0 0 1 6-6h2a6 6 0 0 1 6 6v1"/><path d="M16 4a4 4 0 0 1 0 8"/><path d="M19 14a5 5 0 0 1 3 5v2"/></svg>
                        Inscrits
                    </a>
                <?php } ?>

                <?php if ($estConnecte) { ?>
                    <a class="btn btn-small" href="deconnexion.php">Se déconnecter</a>
                <?php } else { ?>
                    <a href="connexion.php">Connexion</a>
                    <a class="btn btn-small" href="inscription.php">Créer un compte</a>
                <?php } ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if (isset($_SESSION['flash'])) { ?>
            <div class="flash flash-<?= htmlspecialchars($_SESSION['flash']['type']) ?>">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php } ?>