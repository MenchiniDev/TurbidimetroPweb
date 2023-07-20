<?php  
	
	require_once __DIR__ . "/../config.php";
    require DIR_UTIL . "dbConfig.php"; 			// includes database class
    $turbidimeterDataDb = new turbidimeterDataDbManager(); // creates a new istance of Database Class

	class turbidimeterDataDbManager {
		/*private $mysqli_conn = null;
	
		function __construct(){
			$this->openConnection();
		}
    */
    	function openConnection(){	
			if(!$this->isOpened()){
			global $dbHostname;
    		global $dbUsername;
    		global $dbPassword;
    		global $dbName;

			try {
    		// Creazione della connessione utilizzando PDO
    		$dsn = "mysql:host=$dbHostname;dbname=$dbName;charset=utf8mb4";
    		$pdo = new PDO($dsn, $dbUsername, $dbPassword);

    // Configurazione della modalità di gestione degli errori di PDO per lanciare eccezioni in caso di problemi
    		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Esempio di query: selezionare dati da una tabella chiamata "utenti"
			} catch (PDOException $e) {
    // In caso di errore durante la connessione o la query
    			echo "Errore: " . $e->getMessage();
			}		
		}
		
    
    	//Check if the connection to the database id opened
    	function isOpened(){
			try {
				global $dbHostname;
				global $dbUsername;
				global $dbPassword;
				global $dbName;
				// Creare la connessione usando PDO
				$dsn = "mysql:host=$dbHostname;dbname=$dbName;charset=utf8mb4";
				$pdo = new PDO($dsn, $dbUsername, $dbPassword);
		
				// Configurare il modo di gestione degli errori di PDO per lanciare eccezioni in caso di problemi
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
				// La connessione è stata stabilita con successo
				return true;
			} catch (PDOException $e) {
				// In caso di errore durante la connessione
				return false;
			}
		}
	}

   		// Executes a query and returns the results
		function performQuery($queryText) {
			if (!$this->isOpened())
				$this->openConnection();
			
			return $this->mysqli_conn->query($queryText);
		}
		
		function sqlInjectionFilter($parameter){
			if(!$this->isOpened())
				$this->openConnection();
				
			return $this->mysqli_conn->real_escape_string($parameter);
		}

		function closeConnection(){
 	       	//Close the connection
 	       	if($this->mysqli_conn !== null)
				$this->mysqli_conn->close();
			
			$this->mysqli_conn = null;
		}
	}

?>
