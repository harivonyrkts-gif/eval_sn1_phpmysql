<?php
session_start();

$pdo = new PDO('mysql:host=127.0.0.1;dbname=agenda;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

const CATEGORIES = [
    'concert'    => 'Concert',
    'conference' => 'Conférence',
    'atelier'    => 'Atelier',
    'sport'      => 'Sport',
];

function h($texte): string
{
    return htmlspecialchars((string) $texte, ENT_QUOTES);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function estAdmin(): bool
{
    return ($_SESSION['user']['role'] ?? null) === 'admin';
}

function exigerAdmin(): void
{
    if (!estAdmin()) {
        redirect('index.php');
    }
}

function dateFr(string $date, string $format = 'd/m/Y · H\hi'): string
{
    return date($format, strtotime($date));
}
