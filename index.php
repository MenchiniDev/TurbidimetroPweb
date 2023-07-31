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
		<script src="./js/maps.js"></script>
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
		<div id="notificheDiv" onclick="showNotifiche()"><img src="./img/notification.png" alt="area notifiche" id="notifImg">
			<div id="allNotifiche">
				<?php
				/* recupero tutte le notifiche in ordine crescente di tempo*/
				?>
			</div>
		</div>
		<div id="turbidityLineChartDiv">
			<svg id="turbidityLineChartSvg" width="1000" height="900"></svg>
			<div id="map"></div>
		</div>
		<div id="lateralAdd" class="lateral" onclick="moveLateral(1)">Aggiungi Turbidimetro<img src="./img/add.png" alt="aggiungi turbidimetro"></div>
		<div>
		<div id="lateralRm" class="lateral" onclick="moveLateral(2)"> Rimuovi Turbidimetro<img src="./img/remove.png" alt="rimuovi turbidimetro"></div>
		<div>
		<div id="lateralMd" class="lateral" onclick="moveLateral(3)"> Modifica Turbidimetro<img src="./img/modify.png" alt="modifica turbidimetro"></div>
		<div id="addDiv" class="formT">
		<div id="exitAdd" class="exit" onclick="retLat()">>></div>
			<form id="turbidimeterForm" >
				<h2>Aggiungi un Nuovo turbidimetro</h2>
				<label for="id">id</label><br>
				<input type="text" id="identificatore" name="identificatore" ><br>
				<label for="latitudine">Latitudine:</label> <br>
    			<input type="text" id="latitudine" name="latitudine"><br>
    			<label for="longitudine">Longitudine:</label><br>
    			<input type="text" id="longitudine" name="longitudine" >
				<button type="submit" id="addbtn">Invia</button>
			</form>
		</div>
		<div id="rmDiv" class="formT">
		<div id="exitrm" class="exit" onclick="retLat()">>></div>
			<form id="removeForm">
				<h2>Rimuovi un turbidimetro</h2>
				<label for="id">id</label><br>
				<input type="text" id="identificatorerm" name="identificatorerm" ><br>
				<button type="submit" id="rmbtn">Rimuovi</button>
			</form>
		</div>
		<div id="mdDiv" class="formT">
		<div id="exitmd" class="exit" onclick="retLat()">>></div>
			<form id="modifyForm" >
				<h2>modifica la posizione di un turbidimetro</h2>
				<label for="id">id</label><br>
				<input type="text" id="identificatoremd" name="identificatoremd"><br>
				<label for="latitudine">Nuova latitudine:</label> <br>
    			<input type="text" id="latitudinemd" name="latitudinemd"><br>
    			<label for="longitudine">Nuova longitudine:</label><br>
    			<input type="text" id="longitudinemd" name="longitudinemd" >
				<button type="submit" id="mdbtn">modifica</button>
			</form>
		</div>
	<script type="text/javascript" src="./js/index.js"></script>
	</body>
	</html>
	