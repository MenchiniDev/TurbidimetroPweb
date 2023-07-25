<?php
	require_once __DIR__ . "/php/config.php";
	require_once DIR_UTIL . "dataManagerDB.php";	
	require DIR_UTIL . "dbConfig.php";
?>
	
<!DOCTYPE html>
<html lang="it">
	<head>
        	<meta charset="utf-8">
		<meta name="author" content="Lorenzo Menchini">
		<meta name="keywords" content="turbidimeter">
		<link rel="stylesheet" type="text/css" href="./css/style.css" media="screen">
		<script src="https://d3js.org/d3.v4.min.js"></script>
		<script type="text/javascript" src="./js/turbidityLineChart.js"></script>
		<script type="text/javascript" src="./js/ajax/ajaxManager.js"></script>
		<script type="text/javascript" src="./js/ajax/LineChartHandler.js"></script>
		<script type="text/javascript" src="./js/ajax/lineChartDataDashboard.js"></script>
    	<script src="https://maps.googleapis.com/maps/api/js?key=INSERT_YOUR_API_KEY&callback=initMap&v=weekly"></script>
		<title>Turbidimetri</title>
	</head>
	<body onLoad="LineChartHandler.onNewInterval()">
		<header>
			<a>Dati Turbidimetri</a>
		</header>
		<div id="dataSubmissionDiv">
			<label for="inizioIntervallo">Inizio Intervallo:</label>
			<input type="date" id="inizioIntervallo" name="inizioIntervallo" value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
			<label for="fineIntervallo">Fine Intervallo:</label>
			<input type="date" id="fineIntervallo" name="fineIntervallo" value="<?php echo date('Y-m-d'); ?>">
			<label for="turbidimetri">Turbidimetro:</label>
			<select id="turbidimetri" name="turbidimetri">
				<?php
				global $turbidimeterDataDb;
				if($turbidimeterDataDb->isOpen())
				{
					try
					{
						$res = getTurbidimeters();
						while ($row = $stmt->fetch()) {
							echo'<option value=' .$row['turbidimeterID'] . '>' .$row['turbidimeterID'] . '</option>';
						}
						
					}catch (PDOException $e) {
						
						echo "Errore: " . $e->getMessage();
					} // poi da rimuovere
				}else
				{
					$turbidimeterDataDb->openConnection();
					try
					{
					$result = getTurbidimeters(); 
					if ($result->rowCount() > 0) {
						while ($row = $result->fetch()) {
							echo'<option value=' .$row['turbidimeterID'] . '>' .$row['turbidimeterID'] . '</option>';
						}
					}
				}catch (PDOException $e) {	
					echo "<option>Errore: " . $e->getMessage() . "</option>";
				} // poi da rimuovere
			}
			?>
			</select>
			<button id="visualizzaDati" onclick="LineChartHandler.onNewInterval()">Visualizza</button>
			<button id="esportaCSV" onclick="lineChartDataDashboard.exportCSVData()">Esporta CSV</button>
		</div>
		<div id="turbidityLineChartDiv">
			<svg id="turbidityLineChartSvg" width="1000" height="900"></svg>
			<div id="map"></div>
		</div>
		<div id="lateralAdd" onclick="addTurbidimetro()">aggiungi Turbidimetro +</div>
		<div>
			<form id="turbidimeterForm">
				<h2>Aggiungi un Nuovo turbidimetro</h2>
				<label for="id">id</label><br>
				<input type="text" id="identificatore" name="identificatore" ><br>
				<label for="latitudine">Latitudine:</label> <br>
    			<input type="text" id="latitudine" name="latitudine"><br>
    			<label for="longitudine">Longitudine:</label><br>
    			<input type="text" id="longitudine" name="longitudine" >
				<button type="submit">Invia</button>
			</form>
		</div>
	<script type="text/javascript" src="./js/index.js"></script>
	</body>
	</html>
	