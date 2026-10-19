<?php
require_once 'config.php';

$erreurs = [];

$title = $date = $information = '';
$category = 'concert';
$places   = 20;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $date        = trim($_POST['date'] ?? '');
    $information = trim($_POST['information'] ?? '');
    $category    = $_POST['category'] ?? '';
    $places      = $_POST['places'] ?? '';

    $dateObjet = DateTime::createFromFormat('Y-m-d\TH:i', $date);

    if ($title === '') {
        $erreurs[] = 'Le titre est obligatoire.';
    }
    if ($date === '') {
        $erreurs[] = 'La date est obligatoire.';
    } elseif (!$dateObjet) {
        $erreurs[] = "La date n'est pas valide.";
    }
    if ($information === '') {
        $erreurs[] = 'Les informations sont obligatoires.';
    }
    if (!in_array($category, array_keys(CATEGORIES), true)) {
        $erreurs[] = "La catégorie n'est pas valide.";
    }
    if (filter_var($places, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
        $erreurs[] = 'Le nombre de places doit être supérieur à 0.';
    }

    $image   = null;
    $fichier = $_FILES['image'] ?? null;

    if ($fichier && $fichier['error'] !== UPLOAD_ERR_NO_FILE) {
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            $erreurs[] = "L'envoi de l'image a échoué.";
        } elseif (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $erreurs[] = "L'image doit être au format JPG, PNG ou WebP.";
        } else {
            $image = 'uploads/' . uniqid() . '.' . $extension;
        }
    }

    if (empty($erreurs)) {
        if ($image) {
            move_uploaded_file($fichier['tmp_name'], __DIR__ . '/' . $image);
        }

        $sth = $pdo->prepare('INSERT INTO event (title, date, information, category, places, image, is_validated)
                              VALUES (:title, :date, :information, :category, :places, :image, 0)');
        $sth->execute([
            'title'       => $title,
            'date'        => $dateObjet->format('Y-m-d H:i:s'),
            'information' => $information,
            'category'    => $category,
            'places'      => (int) $places,
            'image'       => $image,
        ]);

        flash('success', 'Votre événement a bien été ajouté. Il sera visible après validation par un administrateur.');
        redirect('index.php');
    }
}

$pageTitle = 'Ajouter un événement';
require 'header.php';
?>

    <div class="narrow">
        <a class="back-link" href="index.php">← Retour aux événements</a>

        <div class="card">
            <h1>Ajouter un événement</h1>
            <p class="muted">Votre événement sera visible après validation par un administrateur.</p>

            <?php foreach ($erreurs as $erreur) { ?>
                <div class="flash flash-error"><?= h($erreur) ?></div>
            <?php } ?>

            <form class="form" method="post" enctype="multipart/form-data">
                <div>
                    <label for="title" class="required">Titre</label>
                    <input type="text" id="title" name="title" placeholder="Ex. : Soirée jazz au Kiosque" value="<?= h($title) ?>" required>
                </div>

                <div>
                    <label for="date" class="required">Date</label>
                    <input type="datetime-local" id="date" name="date" value="<?= h($date) ?>" required>
                </div>

                <div>
                    <label for="information" class="required">Informations</label>
                    <textarea id="information" name="information" placeholder="Lieu, programme, tarif..." required><?= h($information) ?></textarea>
                </div>

                <div>
                    <label for="category">Catégorie</label>
                    <select id="category" name="category">
                        <?php foreach (CATEGORIES as $cle => $libelle) { ?>
                            <option value="<?= $cle ?>" <?= $category === $cle ? 'selected' : '' ?>><?= $libelle ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label for="places">Nombre de places</label>
                    <input type="number" id="places" name="places" min="1" value="<?= h($places) ?>">
                </div>

                <div>
                    <label for="image">Image à la une</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div class="help-text">JPG, PNG ou WebP.</div>
                </div>

                <button type="submit">Enregistrer l'événement</button>
            </form>
        </div>
    </div>

<?php require 'footer.php'; ?>
