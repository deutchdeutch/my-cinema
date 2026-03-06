<?php
// On utilise dirname(__FILE__) pour être sûr de rester dans le dossier actuel
$config = require dirname(__FILE__) . '/config.php'; 

try {
    // Ajout de 'port' pour être sûr que Railway se connecte au bon endroit
    $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4";
    
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // Utile pour voir si c'est un problème de mot de passe ou de réseau
    header('Content-Type: application/json');
    die(json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]));
}