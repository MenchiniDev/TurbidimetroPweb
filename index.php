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
		<script type="text/javascript" src="./js/ajax/index.js"></script>
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
		</div>
		<div id="map"></div>
    <script
    src="https://maps.googleapis.com/maps/api/js?key=INSERT_YOUR_API_KEY&callback=initMap&v=weekly"
    defer
    ></script>
	<div id="lateralAdd" onclick="addTurbidimetro()">aggiungi Turbidimetro +</div>
	</body>
</html>
