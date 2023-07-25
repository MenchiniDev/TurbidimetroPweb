<?php
	require_once __DIR__ . "/../config.php";
    require_once DIR_UTIL . "turbidimeterDataDbManager.php"; //includes Database Class

	function getTurbidimeters() { //Ci fornisce i turbidimetri da inserire nel campo select
	    global $turbidimeterDataDb;
		$queryText = 'SELECT turbidimeterID ' 
		               . 'FROM turbidimeters ';
		$result = $turbidimeterDataDb->performQuery($queryText);
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

	function removeTurbidimeter($turbidimeterId)
	{
		$try = (int)$turbidimeterId;
		global $turbidimeterDataDb;
		$queryText = "DELETE FROM Turbidimeters WHERE turbidimeterID = ". $try;
		$res = $turbidimeterDataDb->prepareToBind($queryText);

		return  $turbidimeterDataDb->executeStmt($res);
	}

	/*da implementare la modifica */
	function modifyTurbidimeter($turbidimeterId,$latitudine,$longitudine)
	{
		$try = (int)$turbidimeterId;
		$loclatitudine = (int)$latitudine;
		$loclongitudine = (int)$longitudine;

		global $turbidimeterDataDb;
		$queryText = "UPDATE Turbidimeters SET latitudine= ".$loclatitudine.", longitudine= ".$loclongitudine."  WHERE turbidimeterID =" . $try;
		$res = $turbidimeterDataDb->prepareToBind($queryText);

		return  $turbidimeterDataDb->executeStmt($res);
	}
	
?>