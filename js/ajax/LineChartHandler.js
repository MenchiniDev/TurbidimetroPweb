function LineChartHandler(){}

LineChartHandler.DEFAUL_METHOD = "GET";
LineChartHandler.URL_REQUEST = "./php/ajax/lineChartInteraction.php"; 
// file php che gestisce le richieste Ajax di questo file .js
LineChartHandler.ASYNC_TYPE = true;

LineChartHandler.SUCCESS_RESPONSE = "0";
LineChartHandler.NO_DATA = "-1";

LineChartHandler.onNewInterval = 
	function() {
		let queryString = "?turbidimeterId=" + document.getElementById("turbidimetri").value + "&beginningDate=" + document.getElementById("inizioIntervallo").value + "&endDate=" + document.getElementById("fineIntervallo").value;
		let url = LineChartHandler.URL_REQUEST + queryString;
		let responseFunction = LineChartHandler.onAjaxResponse;
	
		AjaxManager.performAjaxRequest(LineChartHandler.DEFAUL_METHOD, 
										url, LineChartHandler.ASYNC_TYPE, 
										null, responseFunction)
	}

LineChartHandler.onAjaxResponse = 
	function(response){ //valore data parsato dalla onreadystatechange
		console.log("onAjaxResponse dati ricevuti post richiesta");
		console.log(response.message);
		console.log(response.responseCode);
		if (response.responseCode === LineChartHandler.NO_DATA){
			//console.log(response.message);	
			console.log("non è andata a buon fine");
			//messaggio di default a tutto schermo		
			lineChartDataDashboard.showNoData();
			return;
		}
		
		if (response.responseCode === LineChartHandler.SUCCESS_RESPONSE){
			console.log("onAjaxResponse Data ok");
			lineChartDataDashboard.showIntervalData(response.data);
		}
	}
