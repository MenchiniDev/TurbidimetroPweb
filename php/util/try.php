<?php
// Configurazione della connessione al database
$dbHostname = "localhost";
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName = "turbidimetersData";	

try {
    // Creazione della connessione utilizzando PDO
    $dsn = "mysql:host=$dbHostname;dbname=$turbidimetersData;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUsername, $dbPassword);

    // Configurazione della modalità di gestione degli errori di PDO per lanciare eccezioni in caso di problemi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Esempio di query: selezionare dati da una tabella chiamata "utenti"
    $query = "SELECT * FROM utenti";
    $statement = $pdo->prepare($query);
    $statement->execute();

    // Ottenere i risultati
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Fare qualcosa con i risultati
    foreach ($results as $row) {
        echo "ID: " . $row['id'] . ", Nome: " . $row['nome'] . ", Email: " . $row['email'] . "<br>";
    }
} catch (PDOException $e) {
    // In caso di errore durante la connessione o la query
    echo "Errore: " . $e->getMessage();
}
?>
