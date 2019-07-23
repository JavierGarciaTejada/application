$(function(){


	var e = {
        url: path + "index.php/record/"
    }


	var listadoIngeniero = function(){

		getJson(e.url + "getRecords", null, function(a){
			var items = [];
			var ind = 1;
            $.each(a.data, function(i, v){
            	var tr = $("<tr>").addClass('link-item').attr({id: v.ig});
            	tr.append( $("<td>").html(ind) );
            	tr.append( $("<td>").html(v.nombre) );
            	tr.append( $("<td>").html(v.cl) );
            	tr.append( $("<td>").html(v.total) );
            	items.push( tr[0].outerHTML );
            	ind++;
            })
            $("#table-record tbody").html( items.join("") );
            $("#table-record").DataTable({"language" : lenguageTable});
        });

	}

	listadoIngeniero();

	$("#table-record").on('click', '.link-item', function(){

		var ig = $(this).attr('id');
        var filtros = { ingeniero: ['a.ig', '=', ig, 'string'] };
        var dataGet = { filtros: filtros };
        var countDetalle = 1;

        getJson(e.url + "getEvaluacionesIngenieroEtapa", dataGet, function(a){

        	$.each(a.data, function(i, v){
        		a.data[i]['y'] = parseInt(v.y);
        	})
        	
        	var d = {
        		values: a.data,
        		titulo: "Evaluaciones por etapa"
        	};

        	indicadorPieChart('indicador-etapa', d);

        });

        getJson(e.url + "EvaluacionesPromedioLiberacion", dataGet, function(a){

        	$.each(a.data, function(i, v){
        		a.data[i]['y'] = parseInt(v.y);
        	})
        	
        	var d = {
        		values: a.data,
        		titulo: "Tiempos promedio de liberación (Días)"
        	};

        	indicadorPieChart('indicador-tiempos', d);

        });


        $( '#table-record-detalle' ).DataTable( {
			"processing" : true,
			"language" : lenguageTable,
			"scrollX" : true,
			"scrollY" : '62vh',
			"scrollCollapse" : true,
			"orderCellsTop": true,
	        "fixedHeader": true,
	        "destroy": true,
	        // "searching": false,
			"ajax" : {
				"url" : e.url + "getEvaluacionesIngeniero",
				"type" : "GET"
				,data: dataGet
				,beforeSend: function(){
					$( '#modal-loader' ).modal( 'show' );
				}
				// ,success: function(a){
				// 	console.log(a);
				// }
			},
			initComplete: function(){
				$( '#modal-loader' ).modal( 'hide' );
			},
			lengthChange: false,
			dom: 'Bfrtip',
	        buttons: [
	            'excel'
	        ],
			"columns" : [
				{
					"targets" : 0,
					"class" : "details-control",
					"orderable" : false,
					"data" : null,
					"defaultContent" : "",
					"searchable" : false,
					"createdCell" : function( td, data ) {

						$( td ).html( countDetalle++ );
					}
				},
				{ "data" : "el"},
				{ "data" : "no"},
				{ "data" : "etapa"}
			]
			,"rowCallback": function( row, data ) {
				$('td:eq(3)', row).css('background-color', data.color_etapa );
			}

		} );

	});



	var indicadorPieChart = function(id, data, callback = null){

		console.log(data);

        Highcharts.chart(id, {
            chart: {
                height: 420,
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: data.titulo,
                style: {
                    color: "#0282EB"
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                pointFormat: ' <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                },
                series: {
                    cursor: 'pointer',
                    events: {
                        click: callback
                    }
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: data.values
          //       data: [
		        //     { name: 'Chrome', y: 61.41 },
		        //     { name: 'Internet Explorer', y: 11.84 },
		        //     { name: 'Firefox', y: 10.85 },
		        //     { name: 'Edge', y: 4.67 },
		        //     { name: 'Safari', y: 4.18 },
		        //     { name: 'Other', y: 7.05 }
		        // ]
            }]
        });

    }

	

})