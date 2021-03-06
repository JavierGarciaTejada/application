$(function(){
	
	var e = {
		url: path + "index.php/evaluaciones/",
		urlCliente: path + "index.php/evaluacionescliente/",
		areas: null,
		siglas: null,
		proveedores: null,
		tipos: null,
		prioridad: null
	}

	$("#fecha_compromiso").val();
	$("#fecha_solicitud").val( moment().format("YYYY-MM-DD") );
	$(".fecha").datepicker({ dateFormat: 'yy-mm-dd' });


	var diasLiberacion = [ moment().format("YYYY-MM-DD") ];
	for(var i = 1; i <= 5; i++){
		diasLiberacion.push( moment().subtract(i,'d').format('YYYY-MM-DD') );
	}

	var setDiasLiberacion = function(){
		var dias = []
		$.each(diasLiberacion, function(i, v){
			var item = "<option value='"+v+"'>"+v+"</option>";
			dias.push(item);
		})
		$("#fecha_liberacion, #fecha_cancelacion").html( dias.join("") );
	}

	var setAreasLab = function(){
		e.areas = getJson(e.url + "getAreasLab", null, function(a){
			setValuesSelect('al', a.data, 'ix', 'no', 'gr');
		});
	}

	var setSiglasCli = function(){
		e.siglas = getJson(e.url + "getSiglasCli", null, function(a){
			setValuesSelect('ac', a.data, 'ix', 'cl', '');
		});
	}

	var setProveedores = function(){
		e.proveedores = getJson(e.url + "getProveedores", null, function(a){
			setValuesSelect('pv', a.data, 'ix', 'no', '');
		});
	}

	var setTipoEvaluacion = function(){
		e.tipos = getJson(e.url + "getTipoEval", null, function(a){
			setValuesSelect('ts', a.data, 'ix', 'no', '');
		});
	}

	var setPrioridad = function(){
		e.prioridad = getJson(e.url + "getPrioridad", null, function(a){
			setValuesSelect('pr', a.data, 'ix', 'no', '');
		});
	}

	var setResultados = function(){
		e.prioridad = getJson(e.url + "getResultados", null, function(a){
			setValuesSelect('resultado_lib', a.data, 'ix', 'no', '');
		});
	}

	var setNuevos = function(){
		e.prioridad = getJson(e.url + "getNuevos", null, function(a){
			setValuesSelect('nu', a.data, 'ix', 'no', '');
		});
	}

	var setIngenieros = function(){
		e.prioridad = getJson(e.url + "getUsuariosGerencia", null, function(a){
			setValuesSelect('sg', a.Subgerente, 'ix', 'nombre', '');
			setValuesSelect('ig', a.Ingeniero, 'ix', 'nombre', '');
		});
	}

	var setTecnologiaEquipo = function(){
		e.prioridad = getJson(e.url + "getTecnologiaEquipo", null, function(a){
			setValuesSelect('te', a.data, 'ix', 'no', '');
		});
	}

	var setProyectoAsociado = function(){
		e.prioridad = getJson(e.url + "getProyectoAsociado", null, function(a){
			setValuesSelect('pa', a.data, 'ix', 'no', '');
		});
	}

	var setMercado = function(){
		e.prioridad = getJson(e.url + "getMercado", null, function(a){
			setValuesSelect('me', a.data, 'ix', 'no', '');
		});
	}

	var setMeta = function(){
		var meta = [
			{ value: moment().add(15, 'day').format("YYYY-MM-DD"), text: '15 Días' },
			{ value: moment().add(30, 'day').format("YYYY-MM-DD"), text: '30 Días' },
			{ value: moment().add(60, 'day').format("YYYY-MM-DD"), text: '60 Días' }
		];
		setValuesSelect('meta', meta, 'value', 'text', '');
	}

	var setListadoAnexos = function(id){
		var data = { id: id };
		var urlRef = path + "anexos/evaluaciones/";
		$("#anexos_listado").html('');
		getJson(e.url + "getAnexos", data, function(a){
			if( a == false )
				return 0;
			
			var anexos = [];
			$.each(a, function(i, v) {
				anexos.push("<li><a href='"+ urlRef + v.no_generado +"'> "+ v.no +" </a></li>");
			})
			$("#anexos_listado").html(anexos.join(''));
			
		});
	}

	setAreasLab();
	setSiglasCli();
	setProveedores();
	setTipoEvaluacion();
	setPrioridad();
	setResultados();
	setNuevos();
	// setIngenieros();
	setTecnologiaEquipo();
	setProyectoAsociado();
	setMercado();
	setMeta();
	setDiasLiberacion();

	$('#table-evaluaciones thead tr').clone(true).appendTo( '#table-evaluaciones thead' );
    $('#table-evaluaciones thead tr:eq(1) th').each( function (i) {
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

	var tableEvaluaciones = $( '#table-evaluaciones' ).DataTable( 
	{
		"processing" : true,
		"language" : lenguageTable,
		"scrollX" : true,
		"scrollY" : '62vh',
		"scrollCollapse" : true,
		"orderCellsTop": true,
        "fixedHeader": true,
        // "searching": false,
		"ajax" : {
			"url" : path + "index.php/evaluaciones/getEvaluacionesJson/",
			"type" : "GET"
			// ,success: function(a){
			// 	console.log(a);
			// }
		},
		lengthChange: false,
		dom: 'Bfrtip',
        buttons: [
            'excel',
            {
                text: 'Nueva Solicitud',
                action: function ( dt ) {
                	var title = "Nueva Solicitud de Evaluación";
                	$("#evaluacion")[0].reset(); //LIMPIA EL FORMULARIO DE REGISTRO
                	$("#ix, #id").val("");
                	$("#evaluacion .inp_nue_edit").attr({disabled: false}); //HABILITA LOS INPUTS SI ES QUE ESTAN DESABILITADOS
                	$("#btn-guardar-eval").show();//HABILITA BOTON PARA GUARDAR
                	$(".div-ope").hide();//OCULTA LOS FORMULARIOS DE OPRACIONES
                	openModalTitle('#modal-evaluacion', '#modal-head-title', title); //DESPLIEGA EL MODAL
                }
            }

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

					var botones = [];
					// if( data.id_etapa == '1' ){//NUEVA SOLICITUD
					// 	botones.push('<button class="btn btn-sm btn-primary aceptar-eval" id='+ data.id +' title="Aceptar Evaluación"><i class="fa fa-check-circle" aria-hidden="true"></i></button>');
					// 	botones.push('<button class="btn btn-sm btn-danger rechazar-eval" id='+ data.id +' title="Rechazar Evaluación"><i class="fa fa-times-circle" aria-hidden="true"></i></button>');
					// }
					// else if( data.id_etapa == '2' ){
					// 	botones.push('<button class="btn btn-sm btn-info reenviar-eval" id='+ data.id +' title="Reenviar Evaluación"><i class="fa fa-share" aria-hidden="true"></i></button>');
					// }
					// else if( data.id_etapa == '3' ){//EN PROCESO
					// 	botones.push('<button class="btn btn-sm btn-default edit-eval" id='+ data.id +' title="Editar Evaluación"><i class="fa fa-pencil" aria-hidden="true"></i></button>');
					// 	botones.push('<button class="btn btn-sm btn-success liberar-eval" id='+ data.id +' title="Liberar Evaluación"><i class="fa fa-check" aria-hidden="true"></i></button>');
					// 	botones.push('<button class="btn btn-sm btn-warning cancelar-eval" id='+ data.id +' title="Cancelar Evaluación"><i class="fa fa-times" aria-hidden="true"></i></button>');
					// }
					botones.push('<button class="btn btn-sm btn-anexo anexo-eval" id='+ data.id +' title="Cargar Anexos"><i class="fa fa-file" aria-hidden="true"></i></button>');
					$( td ).html( '<div style="width: 110px;">' + botones.join(' ') + '</div>' );
				}
			},
			{ "data" : "el"},
			{ "data" : "cl"},
			{ 
				"data" : "no",
				"createdCell" : function( td, data ) {
					$( td ).html(
						'<textarea name="textarea" readonly rows="3" cols="50">'+data+'</textarea>'
					)
				}
			},
			{ "data" : "s_cliente"},
			{ "data" : "s_lab"},
			{ "data" : "proveedor"},
			{ "data" : "subgerente"},
			{ "data" : "ingeniero"},
			{ "data" : "nuevo"},
			{ "data" : "tec_equipo"},
			{ "data" : "proyecto_asociado"},
			{ "data" : "mercado"},
			{ "data" : "tipo_solicitud"},
			{ "data" : "rl"},
			{ "data" : "etapa"},
			{ "data" : "prioridad"},
			{ "data" : "resultado"},
			{ "data" : "dif"},
			// { "data" : "periodo"},
			// { 
			// 	"data" : null,
			// 	"createdCell" : function( td, data ) {
			// 		$( td ).html(
			// 			data.dilacion_desc+'<br>'+data.dilacion
			// 		)
			// 	}
			// },
			{ "data" : "f_sol"},
			{ "data" : "f_com"},
			{ "data" : "f_lib"},
			{ "data" : "f_can"}
			// { 
			// 	"data" : null,
			// 	"createdCell" : function( td, data ) {
			// 		var fechas = { fs: data.fs.split(' '), fo: data.fo.split(' '), fl: data.fl.split(' '), fc: data.fc.split(' ')};
			// 		$( td ).html(
			// 			"<td align='center' style='width:200px; white-space:nowrap;' >Sol : "+fechas.fs[0]+"<br>Com : "+fechas.fo[0]+"<br>Lib : "+fechas.fl[0]+"<br>Can : "+fechas.fc[0]+"<br></td>"
			// 		)
			// 	}
			// }
		],
		"rowCallback": function( row, data ) {
			$('td:eq(15)', row).css('background-color', data.color_etapa );
			$('td:eq(16)', row).css('background-color', data.color_prioridad );
			$('td:eq(17)', row).css('background-color', data.color_resultado );
			$('td:eq(19)', row).css('background-color', data.color_periodo );
			$('td:eq(20)', row).css('background-color', data.color_dilacion );
		}
	} );

	tableEvaluaciones.buttons().container().appendTo( '#example_wrapper .col-sm-6:eq(0)' );
	//$("div.dt-buttons").addClass('btn-group');
	$("button.dt-button").addClass('btn btn-primary btn-sm');

	//MUESTRA DIV PARA ACEPTAR/RECHAZAR/LIBERAR/CANCELAR
	var showHideOpe = function(id){
		$(".div-ope").hide();
		$("#form-"+id)[0].reset();
		$("#div-"+id).show();
		$("#btn-guardar-eval").hide();
	}

	//DESHABILITA INPUTS Y BOTON PARA EDITAR y NUEVA EVALUACION, PARA EL CASO DE ACEPTAR/CANCELAR/LIBERAR
	var disabledEdicionNuevo = function(sts){
		$("#evaluacion .inp_nue_edit").attr({disabled: sts});
		if( sts == true ) $("#btn-guardar-eval").hide();
		else $("#btn-guardar-eval").show();
	}

	//SETEA LOS DATOS EN EL FORMUALRIO DEL MODAL
	var setValoresFormulario = function(row, formId){

		$(formId)[0].reset();
    	var dataRow = tableEvaluaciones.row( row.parents('tr') ).data();
    	$.each(dataRow, function(i, v){
    		$(formId +" #"+i).val(v);
    	})

	}


	//BOTON EDITAR EVALUACION
	$(document).on('click', '.edit-eval', function(){
    	// setValoresFormulario( $(this), "#evaluacion" );
    	// openModalTitle('#modal-evaluacion', '#modal-head-title', "Modificar Evaluación");
    	$("#form-aceptar").data('bootstrapValidator').resetForm();
		setValoresFormulario( $(this), "#evaluacion" );

		var dataRow = tableEvaluaciones.row( $(this).parents('tr') ).data();
		$("#proceso").val(0);
		if(dataRow.etapa == "En Proceso"){
			$("#proceso").val(1);
			disabledEdicionNuevo(false);
			showHideOpe("aceptar");
			// $("#btn-aceptar-eval, #meta").hide();
			// $("#ft").show();
			setValoresFormulario( $(this), "#form-aceptar" );
		}
		$("#btn-guardar-eval").show();
		openModalTitle('#modal-evaluacion', '#modal-head-title', "Modificar Evaluación");

	})

	//BOTON PARA AGREGAR DOCUMENTO ANEXO
	$(document).on('click', '.anexo-eval', function(){
		var id = $(this).attr('id');
		$("#id_eval").val(id);
		$(".messages").hide();
		setListadoAnexos(id);
		$("#modal-anexo").modal('show');
	})


	//BOTON GUARDAR MODAL EVALUACIONES
	$("#btn-guardar-eval").click(function(){

		var eleIx = $("form#evaluacion #ix").val();
		var ope = ( eleIx == "" ) ? 1 : 2;

		if( ope == 1 )
			registrarEvaluacion();
		else{
			if($("#proceso").val() == 1)
				editarEvaluacionProceso();
			else
				editarEvaluacion();
		}

	});

	//ENVIO CORREO NOTIFICACION
	var notificacionCorreo = function(ele){
		var filtros = {
            ident: ['a.el', '=', $("#el").val(), 'string']
        };
        var dataPost = { filtros: filtros };
    	setPost(e.url + "notificacionCorreo", dataPost, function(response){
			// console.log(response);
		});
	}

	//SI ES UN NUEVO REGISTRO
	var registrarEvaluacion = function(){

		var validator = $('#evaluacion').data('bootstrapValidator');
        validator.validate();
        if (!validator.isValid())
			return false;

		var serial = $("form#evaluacion").serialize();
		serial += "&gr=" + $( "#al option:selected" ).attr('data-ref');
		setPost(e.url + "registraEvaluacion", serial, function(response){
			//console.log(response);
			if( response === true ){
				notificacionCorreo();
				mensaje = "Se registro la evaluación.";
				clase = "alertify-success";
				$("#modal-evaluacion").modal('hide');
				tableEvaluaciones.ajax.reload();
			}else{
				mensaje = "Ocurrio un error al registrar.";
				clase = "alertify-danger";
			}

			alertMessage(mensaje, clase);
		});

	}

	//EDITAR EVALUACION
	var editarEvaluacion = function(){

		var serial = $("form#evaluacion").serialize();
		setPost(e.url + "editarEvaluacion", serial, function(response){
			//console.log(response);
			if( response === true ){
				mensaje = "Se actualizo la evaluación.";
				clase = "alertify-success";
				$("#modal-evaluacion").modal('hide');
				tableEvaluaciones.ajax.reload();
			}else{
				mensaje = "Ocurrio un error al actualizar.";
				clase = "alertify-danger";
			}

			alertMessage(mensaje, clase);
		});

	}

	//EDITAR EVALUACION EN PROCESO (SON MAS DATOS)
	var editarEvaluacionProceso = function(){
		var data = {
			evaluacion: $("form#evaluacion").serialize(),
			lab: $("form#form-aceptar").serialize()
		}
		setPost(e.url + "editarEvaluacionProceso", data, function(response){
			console.log(response);
			if( response === true ){
				mensaje = "Se actualizo la evaluación.";
				clase = "alertify-success";
				$("#modal-evaluacion").modal('hide');
				tableEvaluaciones.ajax.reload();
			}else{
				mensaje = "Ocurrio un error al actualizar.";
				clase = "alertify-danger";
			}

			alertMessage(mensaje, clase);
		});

	}

	//ANEXO DE EVALUACION
	$(".messages").hide();
	function showMessage(message){
		$(".messages").html("").show();
		$(".messages").html(message);
	}

    $( document ).on('change','#file-evaluacion' , function(){
        var file = $("#file-evaluacion")[0].files[0];
        var fileName = file.name;
        showMessage("<span class='info'>Archivo para subir: <strong>"+fileName+"</strong></span>");
	});

	var registraArchivoBD = function(result){

		var data = {
			no: result.nombre,
			no_generado: result.nombreGenerado,
			rx: $("#id_eval").val(),
			tp: 'alta'
		}
		setPost(e.url + "registaAnexo", data, function(response){
			console.log(response);
			setListadoAnexos($("#id_eval").val());
		});
  
	}
	
	$('#btn-file').click(function(ev){
		ev.preventDefault();

		//valida si se cargo archivo para enviar
		if( $("#file-evaluacion")[0].files[0] == undefined )
			return 0;

		//información del formulario
		var formData = new FormData($(".formulario")[0]);
		var message = ""; 

		//hacemos la petición ajax  
		$.ajax({
			url: e.url + "cargaAnexosEvaluacion", method: 'POST', dataType: 'json',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){

				$("#btn-file").prop('disabled', true);
				showMessage(message);
				
			},

			success: function(data){

				if( data.estatus === true ) registraArchivoBD( data );
				
				$(".formulario")[0].reset();
				message = $("<span style='font-size: 12px' class='label label-success'>"+ data.mensaje +"</span>");
				showMessage(message);
				$("#btn-file").prop('disabled', false);

			},
			//si ha ocurrido un error
			error: function(){
				message = $("<span class='error'>Ha ocurrido un error.</span>");
				showMessage(message);
			}
		});

		
	})

});