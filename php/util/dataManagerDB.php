<?php
	require_once __DIR__ . "/../config.php";
    require_once DIR_UTIL . "turbidimeterDataDbManager.php"; //includes Database Class

	function getTurbidimeters() { //Ci fornisce i turbidimetri da inserire nel campo select
	    global $turbidimeterDataDb;
		$queryText = 'SELECT turbidimeterID ' 
		               . 'FROM data '
					   . 'GROUP BY turbidimeterID';
		$result = $turbidimeterDataDb->performQuery($queryText);
		/*while ($row = $result->fetch_assoc()){
				echo'<option value=' .$row['turbidimeterID'] . '>' .$row['turbidimeterID'] . '</option>';
		}*/
		$turbidimeterDataDb->closeConnection();
		return $result;
	}
	
	function getTurbidimeterData($turbidimeterId, $beginningDate, $endDate){
		global $turbidimeterDataDb;
		$queryText = 'SELECT timestamp, sensor, infraredOFF, visibleOFF, fullSpectrumOFF, infraredON, visibleON, fullSpectrumON ' 
		               . 'FROM data '
					   .'WHERE turbidimeterID=' . $turbidimeterId . ' AND timestamp>=\'' . $beginningDate . '\' AND timestamp<=\'' . $endDate . '\'';
		//controllare come controllare date antecedenti/post in mySQL
		$result = $turbidimeterDataDb->performQuery($queryText);
		
		$turbidimeterDataDb->closeConnection();
		return $result;
	}

	/* ^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?) REGEXP LATITUDINE */
	/* [-+]?((1[0-7]\d|0?\d{1,2})(\.\d+)?|180(\.0+)?)$ REGEXP LONGITUDINE*/
	function storeTurbidimeter($turbidimeterId,$latitudine,$longitudine)
	{
		$try = (int)$turbidimeterId;
		global $turbidimeterDataDb;
		$queryText = "INSERT INTO Turbidimeters values"
			. "(:turbidimeterID,:latitudine,:longitudine);";
		$res = $turbidimeterDataDb->prepareToBind($queryText);

		$res->bindParam(":turbidimeterID",$try);
		$res->bindParam(":latitudine",$latitudine);
		$res->bindParam(":longitudine",$longitudine);

		return  $turbidimeterDataDb->executeStmt($res);
	}
	
?>