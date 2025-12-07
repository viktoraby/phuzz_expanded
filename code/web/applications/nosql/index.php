<?php

ini_set('display_errors', 1);  // Fehler anzeigen
error_reporting(E_ALL);        // Alle Fehler melden

// MongoDB-Verbindungsdetails
$mongoHost = "mongodb";  // Docker-Containername für MongoDB als Host
$mongoUsername = 'root';
$mongoPassword = 'password';
$mongoDatabase = 'mymdb';  // Die MongoDB-Datenbank, auf die du zugreifen möchtest

// Standardmeldung
$message = "";

// Automatisierter MongoDB-Login beim Laden der Seite
try {
    // MongoDB-Verbindungs-URI
    $uri = "mongodb://$mongoUsername:$mongoPassword@$mongoHost:27017/$mongoDatabase?authSource=admin";
    
    // MongoDB-Client verbinden
    $client = new MongoDB\Driver\Manager($uri);
    
    // Befehl zum Überprüfen der Verbindung
    $command = new MongoDB\Driver\Command([
        'ping' => 1  // Sendet einen einfachen "ping"-Befehl, um die Verbindung zu testen
    ]);
    
    // Führe den Befehl aus
    $cursor = $client->executeCommand($mongoDatabase, $command);
    
    // Wenn der Befehl erfolgreich ausgeführt wurde
    $result = $cursor->toArray();
    if (!empty($result)) {
        $message = "Erfolgreich eingeloggt und Verbindung getestet!";
    } else {
        $message = "Login fehlgeschlagen. Benutzername oder Passwort sind falsch.";
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    $message = "Fehler bei der Verbindung zur MongoDB: " . $e->getMessage();
}

// Verarbeiten des URL-Parameters 'action'
$action = isset($_GET['action']) ? $_GET['action'] : null;
$queryResult = null;

if ($action) {
    if ($action === 'cats') {
        // Falls action=cats, gebe die benutzerdefinierte Nachricht aus
        $message = "Katzenpfoten befinden sich in der Datenbank und wurden erfolgreich abgerufen.";
    } else {
        // MongoDB-Abfrage ausführen, falls der action-Parameter nicht "cats" ist
        try {
            // Beispiel: Eine Sammlung 'cats' abfragen
            $filter = json_decode($_GET['action'], true);  // Das Filterobjekt basierend auf der action erhalten
            $query = new MongoDB\Driver\Query($filter);
            $cursor = $client->executeQuery("$mongoDatabase.cats", $query);
            $queryResult = iterator_to_array($cursor);
            $message = "Katzen aus der Datenbank abgerufen!";
        } catch (MongoDB\Driver\Exception\Exception $e) {
            $message = "Fehler bei der Abfrage der Datenbank: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MongoDB Login - Abessinierkatzen</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 80%;
      max-width: 900px;
      margin: 50px auto;
      background-color: #fff;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    .message {
      text-align: center;
      color: #ff0000;
      font-weight: bold;
    }
    .content {
      padding: 20px;
    }
    .info-section {
      margin-bottom: 30px;
    }
    .info-section h3 {
      color: #333;
    }
    .info-section p {
      color: #555;
      font-size: 1.1em;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>MongoDB Login - Abessinierkatzen</h2>

    <!-- Erfolgs- oder Fehlermeldung -->
    <div class="message">
      <?php echo $message; ?>
    </div>

    <div class="content">
      <div class="info-section">
        <h3>Über Abessinierkatzen</h3>
        <p>
          Abessinierkatzen sind eine der ältesten und beliebtesten Katzenrassen. Sie zeichnen sich durch
          ihr einzigartiges, elegantes Aussehen und ihr aktives Wesen aus. Ihr kurzes, glattes Fell hat eine
          besondere "ticked" Textur, bei der jedes Haar mehrere Farben in einem Verlauf hat. Diese Katzen sind
          sehr verspielt, intelligent und anhänglich.
        </p>
      </div>

      <div class="info-section">
        <h3>Charaktermerkmale</h3>
        <p>
          Abessinierkatzen sind bekannt für ihre Neugier und ihre soziale Natur. Sie sind sehr aktiv und benötigen
          viel Bewegung. Diese Katzen sind gerne in der Nähe ihrer Besitzer und mögen es, in die Aktivitäten des
          Haushalts eingebunden zu werden. Abessinier sind nicht nur äußerlich schön, sondern auch äußerst
          klug und können oft einfache Tricks lernen oder sogar Türen öffnen.
        </p>
        <p>
          Sie sind auch dafür bekannt, sehr verspielt zu sein, besonders in jungen Jahren, und sie behalten ihre
          verspielte Art oft bis ins hohe Alter bei. Ihre Intelligenz macht sie zu einer guten Wahl für Besitzer,
          die gerne interaktive und aktive Katzen haben.
        </p>
      </div>

      <div class="info-section">
        <h3>Abfrageergebnisse</h3>
        <p>
          <?php if ($queryResult): ?>
            <pre><?php print_r($queryResult); ?></pre>
          <?php endif; ?>
        </p>
      </div>

    </div>
  </div>

</body>
</html>
