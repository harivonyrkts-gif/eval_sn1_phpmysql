<?php
require_once 'config.php';

if (isset($_SESSION['user'])) {
    redirect('index.php');
}

$erreurs = [];
$email   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $erreurs[] = "L'email est obligatoire.";
    }
    if ($password === '') {
        $erreurs[] = 'Le mot de passe est obligatoire.';
    }

    if (empty($erreurs)) {
        $sth = $pdo->prepare('SELECT * FROM user WHERE email = :email');
        $sth->execute(['email' => $email]);
        $user = $sth->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ];

            flash('success', 'Vous êtes connecté.');
            redirect('index.php');
        }

        $erreurs[] = 'Identifiants invalides.';
    }
}

$pageTitle = 'Connexion';
require 'header.php';
?>

    <div class="narrow">
        <div class="card">
            <h1>Connexion</h1>

            <?php foreach ($erreurs as $erreur) { ?>
                <div class="flash flash-error"><?= h($erreur) ?></div>
            <?php } ?>

            <form class="form" method="post">
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" autocomplete="email" value="<?= h($email) ?>" required autofocus>
                </div>

                <div>
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>
                </div>

                <button type="submit">Se connecter</button>
            </form>

            <p class="form-footer">Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
        </div>
    </div>

<?php require 'footer.php'; ?>
