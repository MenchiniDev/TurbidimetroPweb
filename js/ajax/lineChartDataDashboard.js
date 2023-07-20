
function lineChartDataDashboard(){}

lineChartDataDashboard.dataCSV = null;
lineChartDataDashboard.turbidimeterID = null;
lineChartDataDashboard.beginningDate = null;
lineChartDataDashboard.endDate = null;


lineChartDataDashboard.showIntervalData =
	function(data){
		var dataset1 = [];
		for(var i = 0, j = 0; i < data.length; i++, j++){
			//Considerare che per ogni timestamp ho due valori di due sensori diversi
			if(data[i].timestamp == data[i+1].timestamp){
				dataset1[j] = {date: data[i].timestamp, 
					value: (parseInt(data[i].infraredOFF) + parseInt(data[i].visibleOFF) + parseInt(data[i].fullSpectrumOFF) + parseInt(data[i].infraredON) + parseInt(data[i].visibleON) + parseInt(data[i].fullSpectrumON) + 
							parseInt(data[i+1].infraredOFF) + parseInt(data[i+1].visibleOFF) + parseInt(data[i+1].fullSpectrumOFF) + parseInt(data[i+1].infraredON) + parseInt(data[i+1].visibleON) + parseInt(data[i+1].fullSpectrumON))/120};
				
				i++;
			}
			else
				dataset1[j] = {date: data[i].timestamp, 
					value: (parseInt(data[i].infraredOFF) + parseInt(data[i].visibleOFF) + parseInt(data[i].fullSpectrumOFF) + parseInt(data[i].infraredON) + parseInt(data[i].visibleON) + parseInt(data[i].fullSpectrumON))/60};
		}
		
		dataCSV = dataset1;
		lineChartDataDashboard.turbidimeterID = document.getElementById("turbidimetri").value;
		lineChartDataDashboard.beginningDate = document.getElementById("inizioIntervallo").value;
		lineChartDataDashboard.endDate = document.getElementById("fineIntervallo").value;
		console.log(dataset1);
		console.log(lineChartDataDashboard.beginningDate);
		console.log(lineChartDataDashboard.endDate);
		
		var svg = document.getElementById("turbidityLineChartSvg");
				
		while(svg.firstChild)
			svg.removeChild(svg.firstChild);

		//Le vuole come stringhe
		const parseDate = d3.timeParse("%Y-%m-%d %H:%M:%S");
		
		dataset1.forEach((d) => {
			d.date = parseDate(d.date);
			d.value = Number(d.value);
		});
		
		const X = dataset1.map(d => d.date);
		const Y = dataset1.map(d => d.value);
		
		console.log(dataset1);
		console.log(X);
		console.log(d3.extent(X));
		console.log(d3.min(dataset1));
		console.log(d3.max(dataset1));
		console.log(new Date(d3.min(X)));
		console.log(new Date(d3.max(X)));
		console.log(d3.min(Y));
		console.log(d3.max(Y));
		 

        // Step 3
        var svg = d3.select("#turbidityLineChartSvg"),
			margin = 170,
			marginTop = 20,
			marginBottom = 30,
            width = svg.attr("width") - margin, //300
            height = svg.attr("height") - margin //200

        // Step 4 
        var xScale = d3.scaleTime()
					.domain(d3.extent(X))
					.range([0, width]);
        var yScale = d3.scaleLinear()
					.domain([d3.min(Y), d3.max(Y)])
					.range([height-marginBottom, marginTop]);
            
        var g = svg.append("g")
            .attr("transform", "translate(" + 100 + "," + 100 + ")");

        // Step 5
        // Title
        svg.append('text')
        .attr('x', width/2 + 100)
        .attr('y', 100)
        .attr('text-anchor', 'middle')
        .style('font-family', 'Helvetica')
        .style('font-size', 24)
	.style('font-weight', 'bold')
        .text('Torbidità');
        
        // X label
        /*svg.append('text')
        .attr('x', width/2 + 100)
        .attr('y', height - 15 + 150)
        .attr('text-anchor', 'middle')
        .style('font-family', 'Helvetica')
        .style('font-size', 16)
        .text('Independant');*/
        
        // Y label
        /*svg.append('text')
        .attr('text-anchor', 'middle')
        .attr('transform', 'translate(60,' + height + ')rotate(-90)')
        .style('font-family', 'Helvetica')
        .style('font-size', 16)
        .text('Dependant');*/

        // Step 6
        g.append("g")
         .attr("transform", "translate(0," + 700 + ")")
         .call(d3.axisBottom(xScale));
        
        g.append("g")
         .call(d3.axisLeft(yScale));
        
        // Step 7
        svg.append('g')
        .selectAll("dot")
        .data(dataset1)
        .enter()
        .append("circle")
        .attr("cx", function (d) { return xScale(d.date); } )
		.attr("cy", function (d) { return yScale(d.value); } )
        .attr("r", 3)
        .attr("transform", "translate(" + 100 + "," + 100 + ")")
        .style("fill", "steelblue");

        // Step 8        
        var line = d3.line()
        .x(function(d) { return xScale(d.date); }) 
		.y(function(d) { return yScale(d.value); })
        .curve(d3.curveLinear)
        
        svg.append("path")
        .datum(dataset1) 
        .attr("class", "line") 
        .attr("transform", "translate(" + 100 + "," + 100 + ")")
        .attr("d", line)
        .style("fill", "none")
        .style("stroke", "steelblue")
        .style("stroke-width", "2");
	
	//Dopo aver aggiornato il grafico lo rendo nuovamente visibile, nel caso fosse stato nascosto
	document.getElementById("turbidityLineChartSvg").style.display = "flex";
}

lineChartDataDashboard.showNoData = 
	function(){
		document.getElementById("turbidityLineChartSvg").style.display = "none";
		dataCSV = null;
		alert("Nessun dato da visualizzare per l'intervallo e il turbdimetro selezionati.");

	}
	
//Scarica un file CSV contenente i dati graficati 	
lineChartDataDashboard.exportCSVData = 
	function(){
		var csvContent = "data:text/csv;charset=utf-8,";
		csvContent += "date,value\r\n";

		// Itera attraverso gli elementi dell'array e aggiungi i valori alla stringa CSV
		if(dataCSV){ //Solo se ci sono dati mostrati nel grafico
			dataCSV.forEach(function (item) {
				let row = item.date + "," + item.value;
					csvContent += row + "\r\n";
				});
			}
			
		var encodedUri = encodeURI(csvContent);
		var link = document.createElement("a");
		link.setAttribute("href", encodedUri);
		link.setAttribute("download", "turbidimeter" + lineChartDataDashboard.turbidimeterID + "_" + lineChartDataDashboard.beginningDate + "_" + lineChartDataDashboard.endDate + ".csv");
		document.body.appendChild(link);

		link.click();		
			
		
	}
