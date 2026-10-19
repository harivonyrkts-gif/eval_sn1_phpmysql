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
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'email n'est pas valide.";
    }

    if ($password === '') {
        $erreurs[] = 'Le mot de passe est obligatoire.';
    } elseif (strlen($password) < 6) {
        $erreurs[] = 'Le mot de passe doit faire au moins 6 caractères.';
    }

    if (empty($erreurs)) {
        $sth = $pdo->prepare('SELECT COUNT(*) FROM user WHERE email = :email');
        $sth->execute(['email' => $email]);
        if ($sth->fetchColumn() > 0) {
            $erreurs[] = 'Cet email est déjà utilisé.';
        }
    }

    if (empty($erreurs)) {
        $sth = $pdo->prepare("INSERT INTO user (email, password, role) VALUES (:email, :password, 'user')");
        $sth->execute([
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        flash('success', 'Votre compte a bien été créé, vous pouvez vous connecter.');
        redirect('connexion.php');
    }
}

$pageTitle = 'Créer un compte';
require 'header.php';
?>

    <div class="narrow">
        <div class="card">
            <h1>Créer un compte</h1>

            <?php foreach ($erreurs as $erreur) { ?>
                <div class="flash flash-error"><?= h($erreur) ?></div>
            <?php } ?>

            <form class="form" method="post">
                <div>
                    <label for="email" class="required">Email</label>
                    <input type="email" id="email" name="email" autocomplete="email" value="<?= h($email) ?>" required>
                </div>

                <div>
                    <label for="password" class="required">Mot de passe</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" required>
                    <div class="help-text">6 caractères minimum.</div>
                </div>

                <button type="submit">Créer mon compte</button>
            </form>

            <p class="form-footer">Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
        </div>
    </div>

<?php require 'footer.php'; ?>
