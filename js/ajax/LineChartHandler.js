function LineChartHandler(){}

LineChartHandler.DEFAUL_METHOD = "GET";
LineChartHandler.URL_REQUEST = "./php/ajax/lineChartInteraction.php"; 
//E il file php che gestisce le richieste Ajax di questo file .js
LineChartHandler.ASYNC_TYPE = true;

LineChartHandler.SUCCESS_RESPONSE = "0";
LineChartHandler.NO_DATA = "-1";

LineChartHandler.onNewInterval = 
	function() {
		var queryString = "?turbidimeterId=" + document.getElementById("turbidimetri").value + "&beginningDate=" + document.getElementById("inizioIntervallo").value + "&endDate=" + document.getElementById("fineIntervallo").value;
		var url = LineChartHandler.URL_REQUEST + queryString;
		var responseFunction = LineChartHandler.onAjaxResponse;
	
		AjaxManager.performAjaxRequest(LineChartHandler.DEFAUL_METHOD, 
										url, LineChartHandler.ASYNC_TYPE, 
										null, responseFunction)
	}

LineChartHandler.onAjaxResponse = 
	function(response){
		console.log(response.message);
		console.log(response.responseCode);
		if (response.responseCode === LineChartHandler.NO_DATA){
			console.log(response.message);	
			//messaggio di default a tutto schermo		
			lineChartDataDashboard.showNoData();
			return;
		}
		
		if (response.responseCode === LineChartHandler.SUCCESS_RESPONSE){
			lineChartDataDashboard.showIntervalData(response.data);
			console.log(response.data);
			console.log(response.message);
		}
	}
