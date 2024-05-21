<?php
// Einbinden der Datenbankkonfiguration
require 'config.php';

// Überprüfen, ob ein neues Element zur Einkaufsliste hinzugefügt werden soll
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['artikel']) && isset($_POST['menge']) && isset($_POST['preis'])) {
    // Bereinigen und Validieren der Eingaben
    $artikel = trim($_POST['artikel']);
    $menge = intval($_POST['menge']);
    $preis = floatval($_POST['preis']);
    
    // Überprüfen, ob die Eingaben gültig sind
    if (!empty($artikel) && $menge > 0 && $preis >= 0) {
        // Vorbereiten und Ausführen der SQL-Anweisung zum Einfügen eines neuen Elements
        $stmt = $pdo->prepare('INSERT INTO einkaufsliste (artikel, menge, preis) VALUES (:artikel, :menge, :preis)');
        $stmt->execute(['artikel' => $artikel, 'menge' => $menge, 'preis' => $preis]);
    }
}

// Überprüfen, ob ein Element aus der Einkaufsliste entfernt werden soll
if (isset($_GET['remove'])) {
    // ID des zu entfernenden Elements bereinigen
    $id = intval($_GET['remove']);
    // Vorbereiten und Ausführen der SQL-Anweisung zum Löschen des Elements
    $stmt = $pdo->prepare('DELETE FROM einkaufsliste WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

// Abrufen der Einkaufsliste aus der Datenbank
$stmt = $pdo->query('SELECT * FROM einkaufsliste');
$einkaufsliste = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Einkaufsliste</title>
    <script>
        // Funktion zur Aktualisierung der Gesamtsumme
        function updateTotal() {
            let total = 0;
            // Iteration über alle Listenelemente und Berechnung der Gesamtsumme
            document.querySelectorAll('.item').forEach(item => {
                const menge = parseInt(item.querySelector('.menge').textContent);
                const preis = parseFloat(item.querySelector('.preis').textContent.replace(',', '.'));
                total += menge * preis;
            });
            // Anzeige der Gesamtsumme im entsprechenden Element
            document.getElementById('gesamtsumme').textContent = total.toFixed(2).replace('.', ',') + ' €';
        }

        // Event-Listener zur Aktualisierung der Gesamtsumme beim Laden der Seite
        document.addEventListener('DOMContentLoaded', updateTotal);
    </script>
</head>
<body>
    <h1>Einkaufsliste</h1>

    <!-- Formular zum Hinzufügen neuer Artikel -->
    <form method="post" action="">
        <input type="text" name="artikel" placeholder="Neuer Artikel" required>
        <input type="number" name="menge" placeholder="Menge" min="1" required>
        <input type="number" step="0.01" name="preis" placeholder="Preis" min="0" required>
        <input type="submit" value="Hinzufügen">
    </form>

    <!-- Anzeige der Einkaufsliste -->
    <?php if (!empty($einkaufsliste)): ?>
        <ul id="einkaufsliste">
            <?php foreach ($einkaufsliste as $item): ?>
                <li class="item">
                    <?php echo htmlspecialchars($item['artikel']); ?> -
                    Menge: <span class="menge"><?php echo htmlspecialchars($item['menge']); ?></span> -
                    Preis: <span class="preis"><?php echo number_format($item['preis'], 2, ',', '.'); ?></span> € -
                    <a href="?remove=<?php echo $item['id']; ?>">Entfernen</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Anzeige der Gesamtsumme -->
        <p>Gesamtsumme: <span id="gesamtsumme"></span></p>
    <?php else: ?>
        <p>Die Einkaufsliste ist leer.</p>
    <?php endif; ?>
</body>
</html>
