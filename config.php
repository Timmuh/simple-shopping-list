<?php
// Konfigurationsvariablen für die Datenbankverbindung
$host = ''; // Host der MySQL-Datenbank
$db = ''; // Datenbankname
$user = ''; // Benutzername der MySQL-Datenbank
$pass = ''; // Passwort der MySQL-Datenbank

// Datenbankverbindungs-DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

// Optionen für die PDO-Verbindung
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Fehlerbehandlung durch Auslösen von Ausnahmen
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Standardmäßiger Fetch-Modus
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Versuche, eine Verbindung zur Datenbank herzustellen
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Bei Verbindungsfehler: Script beenden und Fehlermeldung ausgeben
    die('Verbindung fehlgeschlagen: ' . $e->getMessage());
}
?>
