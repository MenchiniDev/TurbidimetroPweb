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
		console.log(queryString);
		let url = LineChartHandler.URL_REQUEST + queryString;
		let responseFunction = LineChartHandler.onAjaxResponse;
	
		AjaxManager.performAjaxRequest(LineChartHandler.DEFAUL_METHOD, 
										url, LineChartHandler.ASYNC_TYPE, 
										null, responseFunction)
	}

LineChartHandler.onAjaxResponse = 
	function(response){ //valore data parsato dalla onreadystatechange
		if (response.responseCode === LineChartHandler.NO_DATA){	
			lineChartDataDashboard.showNoData();
			return;
		}
		
		if (response.responseCode === LineChartHandler.SUCCESS_RESPONSE){
			lineChartDataDashboard.showIntervalData(response.data);
		}
	}
