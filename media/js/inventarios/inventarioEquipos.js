$(function(){
	
	var e = {
		url: path + "index.php/InventarioEquipos/"
	}

	var setValoresFormulario = function(row, formId){

		$(formId)[0].reset();
    	var dataRow = tableInventario.row( row.parents('tr') ).data();
    	$.each(dataRow, function(i, v){
    		$(formId +" #"+i).val(v);
    	})

	}

	var setRed = function(){
		e.siglas = getJson(e.url + "getRed", null, function(a){
			setValuesSelect('red', a.data, 'no', 'no', '');
		});
	}

	$("#red").change(function(){
		var r = ( $(this).val() == "Procesamiento" ) ? 3 : 0;
		$(".procesamiento").attr('readonly', 'readonly');
		if( r == 3)
			$(".procesamiento").attr('readonly', false);
	});

	var setEquipos = function(){
		e.siglas = getJson(e.url + "getEquipos", null, function(a){
			setValuesSelect('equipo', a.data, 'no', 'no', '');
		});
	}

	var setProveedores = function(){
		e.siglas = getJson(e.url + "getProveedores", null, function(a){
			setValuesSelect('proveedor', a.data, 'no', 'no', '');
		});
	}

	setRed();
	setEquipos();
	setProveedores();

	$('#table-inventario thead tr').clone(true).appendTo( '#table-inventario thead' );
    $('#table-inventario thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control input-sm" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( tableEvaluaciones.column(i).search() !== this.value ) {
                tableEvaluaciones
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );
 	
	var tableInventario = $( '#table-inventario' ).DataTable( 
	{
		"processing" : true,
		"language" : lenguageTable,
		"scrollX" : true,
		"scrollY" : '62vh',
		"scrollCollapse" : true,
		"orderCellsTop": true,
        "fixedHeader": true,
        "searching": false,
		"ajax" : {
			"url" : e.url + "getInventarioGeneral/",
			"type" : "GET"
		},
		lengthChange: false,
		dom: 'Bfrtip',
        buttons: [
	        {
	        	extend: 'excelHtml5'
	        },
	        {
	            text: 'Nuevo',
	            action: function ( dt ) {
	            	$("#form-inv-equipos")[0].reset(); //LIMPIA EL FORMULARIO DE REGISTRO
	            	$("#id").val("");
	            	$("#modal-inv-equipos").modal('show');

	            }
	        }
	        
	    ],
		"columns" : [
			{
				"targets" : 0,
				"width": "10%",
				"class" : "details-control",
				"orderable" : false,
				"data" : null,
				"defaultContent" : "",
				"searchable" : false,
				"createdCell" : function( td, data ) {

					var botones = [];
					botones.push( '<button class="btn btn-sm btn-info modificar-inv" id='+ data.id +' title="Actualizar Registro"><i class="fa fa-edit" aria-hidden="true"></i></button>' );
					$( td ).html( '' + botones.join(' ') + '' );
				}
			},
			{ "data" : "red"},
			{ "data" : "equipo"},
			{ "data" : "tecnologia"},
			{ "data" : "proveedor"},
			{ "data" : "modelo_equipo"},
			{ "data" : "gestor"},
			{ "data" : "version_gestor"},
			{ "data" : "version_eq_prod"},
			{ "data" : "cant_eq_ver_prod"},
			{ "data" : "ultima_ver_lib"},
			{ "data" : "cant_eq_act_ult_ver"},
			{ "data" : "porcentaje_eq_act"},
			{ "data" : "cant_eq_inst_red"},
			{ "data" : "prob_sol_ult_ver"},
			{ "data" : "lineas"},
			{ "data" : "usuarios"},
			{ "data" : "troncales"}
			
		]
	} );
	tableInventario.buttons().container().appendTo( '#example_wrapper .col-sm-6:eq(0)' );
	$("button.dt-button").addClass('btn btn-primary btn-sm');



	$("#btn-guardar-inv").click(function(){
		// var validator = $('#form-inv-equipos').data('bootstrapValidator');
  //       validator.validate();
  //       if (!validator.isValid())
		// 	return false;

		var serial = $("#form-inv-equipos").serialize();

		var ope = "registraInventario";
		if( $("#id").val() != "" )
			ope = "modificarInventario";

		setPost(e.url + ope, serial, function(response){
			if( response === true ){
				mensaje = "Se realizó correctamente.";
				clase = "alertify-success";
				$("#modal-evaluacion").modal('hide');
				tableInventario.ajax.reload();
			}else{
				mensaje = "Ocurrio un error.";
				clase = "alertify-danger";
			}
			alertMessage(mensaje, clase);
			$("#modal-inv-equipos").modal('hide');
		});

	})



	//BOTON EDITAR
	$(document).on('click', '.modificar-inv', function(){
		$("#form-inv-equipos")[0].reset();
		setValoresFormulario( $(this), "#form-inv-equipos" );
		$("#modal-inv-equipos").modal('show');
	})

	//BOTON CANCELAR
	// $(document).on('click', '.estado', function(){

 //    	var ope = $(this).attr('data-ref');
 //    	var mov = $(this).attr('data-ope'); 

 //    	var msg = "¿Está seguro de "+mov+" el soporte?";
 //    	if( confirm(msg) ){

 //    		var dataRow = tableInventario.row( $(this).parents('tr') ).data();
 //    		setPost(e.url + ope, dataRow, function(response){
	// 			console.log(response);
	// 			if( response === true ){
	// 				mensaje = "Se realizó correctamente.";
	// 				clase = "alertify-success";
	// 				$("#modal-evaluacion").modal('hide');
	// 				tableInventario.ajax.reload();
	// 			}else{
	// 				mensaje = "Ocurrio un error.";
	// 				clase = "alertify-danger";
	// 			}

	// 			alertMessage(mensaje, clase);
	// 		});

 //    	}

	// })

	// $(document).on('click', '.finalizar', function(){

	// 	$("#form-finalizar")[0].reset();
	// 	$("#id_rep_fin").val( $(this).attr('id') );
	// 	var dataRow = tableInventario.row( $(this).parents('tr') ).data();
	// 	$("#fecha_soporte_upd").val( dataRow.fecha_soporte );
	// 	$("#modal-finalizar").modal('show');

	// })

	// $("#btn-finalizar").click(function(){

	// 	var validator = $('#form-finalizar').data('bootstrapValidator');
 //        validator.validate();
 //        if (!validator.isValid())
	// 		return false;

	// 	var serial = $("#form-finalizar").serialize();
	// 	setPost(e.url + 'finalizarReporte', serial, function(response){
	// 		if( response === true ){
	// 			mensaje = "Se realizó correctamente.";
	// 			clase = "alertify-success";
	// 			$("#modal-evaluacion").modal('hide');
	// 			tableInventario.ajax.reload();
	// 		}else{
	// 			mensaje = "Ocurrio un error.";
	// 			clase = "alertify-danger";
	// 		}

	// 		alertMessage(mensaje, clase);
	// 	});

	// })


	// //BOTON ESCALADO
	// $(document).on('click', '.escalado', function(){
 //    	$("#form-escalado").data('bootstrapValidator').resetForm();
	// 	setValoresFormulario( $(this), "#form-escalado" );
	// 	$("#id_rep_escalado").val( $(this).attr('id') );
	// 	$("#modal-escalado").modal('show');
	// })

	// $("#btn-escalado").click(function(){

	// 	var validator = $('#form-escalado').data('bootstrapValidator');
 //        validator.validate();
 //        if (!validator.isValid())
	// 		return false;

	// 	var serial = $("#form-escalado").serialize();
	// 	setPost(e.url + 'escalarReporte', serial, function(response){
	// 		if( response === true ){
	// 			mensaje = "Se realizó correctamente.";
	// 			clase = "alertify-success";
	// 			$("#modal-evaluacion").modal('hide');
	// 			tableInventario.ajax.reload();
	// 		}else{
	// 			mensaje = "Ocurrio un error.";
	// 			clase = "alertify-danger";
	// 		}

	// 		alertMessage(mensaje, clase);
	// 	});

	// })


	// //BOTON ARCHIVOS
	// $(document).on('click', '.archivos', function(){

	// 	$("#id_rep").val( $(this).attr('id') );
	// 	$(".messages").hide();
	// 	setListadoAnexos($(this).attr('id'));
 //    	$("#modal-anexo").modal('show');

	// })

	// //ANEXO DE EVALUACION
	// $(".messages").hide();
	// function showMessage(message){
	// 	$(".messages").html("").show();
	// 	$(".messages").html(message);
	// }

 //    $( document ).on('change','#file-soporte' , function(){
 //        var file = $("#file-soporte")[0].files[0];
 //        var fileName = file.name;
 //        showMessage("<span class='info'>Archivo para subir: <strong>"+fileName+"</strong></span>");
	// });

	// var registraArchivoBD = function(result){

	// 	var data = {
	// 		no: result.nombre,
	// 		no_generado: result.nombreGenerado,
	// 		rx: $("#id_rep").val(),
	// 		tp: 'liberacion'
	// 	}
	// 	setPost(e.url + "registaAnexo", data, function(response){
	// 		console.log(response);
	// 		setListadoAnexos($("#id_rep").val());
	// 	});
  
	// }
	
	// $('#btn-file').click(function(ev){
	// 	ev.preventDefault();

	// 	//valida si se cargo archivo para enviar
	// 	if( $("#file-soporte")[0].files[0] == undefined )
	// 		return 0;

	// 	//información del formulario
	// 	var formData = new FormData($(".formulario")[0]);
	// 	var message = ""; 

	// 	//hacemos la petición ajax  
	// 	$.ajax({
	// 		url: e.url + "cargaAnexosReporte", method: 'POST', dataType: 'json',
	// 		data: formData,
	// 		cache: false,
	// 		contentType: false,
	// 		processData: false,
	// 		beforeSend: function(){

	// 			$("#btn-file").prop('disabled', true);
	// 			showMessage(message);
				
	// 		},

	// 		success: function(data){

	// 			if( data.estatus === true ) registraArchivoBD( data );
				
	// 			$(".formulario")[0].reset();
	// 			message = $("<span style='font-size: 12px' class='label label-success'>"+ data.mensaje +"</span>");
	// 			showMessage(message);
	// 			$("#btn-file").prop('disabled', false);

	// 		},
	// 		//si ha ocurrido un error
	// 		error: function(){
	// 			message = $("<span class='error'>Ha ocurrido un error.</span>");
	// 			showMessage(message);
	// 		}
	// 	});
		
	// })


});