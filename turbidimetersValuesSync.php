<?php
	require_once __DIR__ . "/php/config.php";
	require_once DIR_UTIL . "dataManagerDB.php";
    
	$turbidimetersTimestamp = array(); //Variabile in cui memorizzo il turbidimetersTimestamp[$turbidimeterIDfolder] dell'ultimo valore ricevuto tramite rsync e inserito nel DB
	//Mi faccio un array di timestamp per scrivere l'ultimo valore che inserisco nel DB per tale turbidimetro e poi vado ad aggiornare da lì in poi

	// Ciclo infinito
	while (true) {
		// Controllo il contenuto della cartella
		$turbidimeterIDfolders = scandir("./values");
		foreach($turbidimeterIDfolders as $turbidimeterIDfolder){
			if ($turbidimeterIDfolder == '.' || $turbidimeterIDfolder == '..' || $turbidimeterIDfolder[0] == '.') 
				continue;
			
			if(!array_key_exists($turbidimeterIDfolder, $turbidimetersTimestamp))
				$turbidimetersTimestamp[$turbidimeterIDfolder] = null;
			
			$dayFolders = scandir("./values/" . $turbidimeterIDfolder);

			if($turbidimetersTimestamp[$turbidimeterIDfolder] == null)	
				echo"L'ultima verifica per la cartella ./values/" . $turbidimeterIDfolder . " risale a: mai\n ";
			else
				echo"L'ultima verifica per la cartella ./values/" . $turbidimeterIDfolder . " risale a: " . $turbidimetersTimestamp[$turbidimeterIDfolder]->format('Y-m-d H:i:s') . "\n";

			// Esamino ogni file nella cartella
			foreach ($dayFolders as $dayFolder) {
				// Ignoro le cartelle e i file nascosti
				if ($dayFolder == '.' || $dayFolder == '..' || $dayFolder[0] == '.') {
					continue;
				}
			
				echo "Esploro la cartella ./values/" . $turbidimeterIDfolder. "/" . $dayFolder . " per verificare se ci sono file non caricati nel DB\n";
				$filesFolders = scandir("./values/" . $turbidimeterIDfolder. "/" . $dayFolder);

				$folderDate = new DateTime($dayFolder);
				//if($turbidimetersTimestamp[$turbidimeterIDfolder] != null)
				//	echo "Array: " . $turbidimetersTimestamp[$turbidimeterIDfolder]->format('Y-m-d') . " Cartella: " . $folderDate->format('Y-m-d') . "\n";
				//else
				//	echo "Array: " . $turbidimetersTimestamp[$turbidimeterIDfolder] . " Cartella: " . $folderDate->format('Y-m-d') . "\n";
				//Se tutti i file nella cartella sono già caricati, la salto direttamente
				if($turbidimetersTimestamp[$turbidimeterIDfolder] != null && $folderDate->format('Y-m-d') < $turbidimetersTimestamp[$turbidimeterIDfolder]->format('Y-m-d'))
					continue;
				
				foreach ($filesFolders as $filesFolder) {
					if ($filesFolder == '.' || $filesFolder == '..' || $filesFolder[0] == '.') {
						continue;
					}

					$index = 0;
					while($index < strlen($filesFolder) && $filesFolder[$index] != ".")
						$index++;
				
					$dateTimestamp = DateTime::createFromFormat('Y-m-d H-i-s', $dayFolder . " " . substr($filesFolder, 3, $index-3));
					//echo"Timestamp ultimo file caricato: " . $turbidimetersTimestamp[$turbidimeterIDfolder]->format('Y-m-d H-i-s') . " Timestamp file: " . $dateTimestamp->format('Y-m-d H-i-s') . "\n";

					if($turbidimetersTimestamp[$turbidimeterIDfolder] == null || $dateTimestamp > $turbidimetersTimestamp[$turbidimeterIDfolder])
						$turbidimetersTimestamp[$turbidimeterIDfolder] = $dateTimestamp;
					else
						continue;


					echo "Inserisco i seguenti dati nel DB:\n";
					echo $dateTimestamp->format('Y-m-d H:i:s') . "\n";
					echo file_get_contents("./values/" . $turbidimeterIDfolder . "/" . $dayFolder . "/" . $filesFolder) . "\n";
			
					$readValues = array();
				
					$file = fopen("./values/" . $turbidimeterIDfolder . "/" . $dayFolder . "/" . $filesFolder, "r");

					if ($file) {
						while (($line = fgets($file)) !== false) {
							$words = explode(' ', $line);
			
							foreach ($words as $word) {
							array_push($readValues, $word);
							}
						}
		
						fclose($file);
					}
			
				global $turbidimeterDataDb;
				$queryText = 'INSERT INTO data(turbidimeterID, timestamp, sensor, infraredOFF, visibleOFF, fullSpectrumOFF, infraredON, visibleON, fullSpectrumON) '
								.'VALUES(\'' . $turbidimeterIDfolder . '\', \'' . $dateTimestamp->format('Y-m-d H:i:s') . '\', \'' . 1 . '\', \'' . intval($readValues[0]) . '\', \'' . intval($readValues[1]) . '\', \'' . intval($readValues[2]) . '\', \'' . intval($readValues[3]) . '\', \'' . intval($readValues[4]) . '\', \'' . intval($readValues[5]) . '\')';
				$result = $turbidimeterDataDb->performQuery($queryText);
				$queryText = 'INSERT INTO data(turbidimeterID, timestamp, sensor, infraredOFF, visibleOFF, fullSpectrumOFF, infraredON, visibleON, fullSpectrumON) '
						   .'VALUES(\'' . $turbidimeterIDfolder . '\', \'' . $dateTimestamp->format('Y-m-d H:i:s') . '\', \'' . 3 . '\', \'' . intval($readValues[6]) . '\', \'' . intval($readValues[7]) . '\', \'' . intval($readValues[8]) . '\', \'' . intval($readValues[9]) . '\', \'' . intval($readValues[10]) . '\', \'' . intval($readValues[11]) . '\')';
				$result = $turbidimeterDataDb->performQuery($queryText);
				$turbidimeterDataDb->closeConnection(); 

				}
		
			}
			echo"\n";
		}
			
			echo"\n*************************************************************************************\n\n";
			sleep(90);   // Attendo 90 secondi prima di controllare di nuovo le cartelle e i file
	}
?>	
