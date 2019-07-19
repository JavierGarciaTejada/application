$(function(){
	
	var e = {
		url: path + "index.php/evaluaciones/",
		areas: null,
		siglas: null,
		proveedores: null,
		tipos: null,
		prioridad: null
	}

	$("#fecha_compromiso").val();
	$("#fecha_solicitud").val( moment().format("YYYY-MM-DD") );
	$(".fecha").datepicker({ dateFormat: 'yy-mm-dd' });

	var puesto = $("#puesto").val();
	var role = $("#role").val();
	var autorizacionCambios = ( puesto == "Gerente" || puesto == "Subgerente" || role == "Administrador") ? true : false;

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
			var todos = a.Subgerente.concat(a.Ingeniero);
			setValuesSelect('sg', a.Subgerente, 'ix', 'nombre', '');
			setValuesSelect('ig', todos, 'ix', 'nombre', '');
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

	setAreasLab();
	setSiglasCli();
	setProveedores();
	setTipoEvaluacion();
	setPrioridad();
	setResultados();
	setNuevos();
	setIngenieros();
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
            'excel',
            {
                text: 'Nueva Solicitud',
                action: function ( dt ) {
                	var title = "Nueva Solicitud de Evaluación";
                	$("#evaluacion").data('bootstrapValidator').resetForm();
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
					if( data.id_etapa == '1' ){//NUEVA SOLICITUD
						botones.push('<button class="btn btn-sm btn-primary aceptar-eval" id='+ data.id +' title="Aceptar Evaluación"><i class="fa fa-check-circle" aria-hidden="true"></i></button>');
						botones.push('<button class="btn btn-sm btn-danger rechazar-eval" id='+ data.id +' title="Rechazar Evaluación"><i class="fa fa-times-circle" aria-hidden="true"></i></button>');
					}
					else if( data.id_etapa == '2' ){
						botones.push('<button class="btn btn-sm btn-info reenviar-eval" id='+ data.id +' title="Reenviar Evaluación"><i class="fa fa-share" aria-hidden="true"></i></button>');
					}
					else if( data.id_etapa == '3' ){//EN PROCESO
						botones.push('<button class="btn btn-sm btn-default edit-eval" id='+ data.id +' title="Modificar Evaluación"><i class="fa fa-pencil" aria-hidden="true"></i></button>');
						botones.push('<button class="btn btn-sm btn-success liberar-eval" id='+ data.id +' title="Liberar Evaluación"><i class="fa fa-check" aria-hidden="true"></i></button>');
						botones.push('<button class="btn btn-sm btn-warning cancelar-eval" id='+ data.id +' title="Cancelar Evaluación"><i class="fa fa-times" aria-hidden="true"></i></button>');
					}

					if(autorizacionCambios)
						$( td ).html( '<div style="width: 110px;">' + botones.join(' ') + '</div>' );
				}
			},
			//{ "data" : "id"},
			//{ "data" : "ix"},
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
			{ "data" : "periodo"},
			{ 
				"data" : null,
				"createdCell" : function( td, data ) {
					$( td ).html(
						data.dilacion_desc+'<br>'+data.dilacion
					)
				}
			},
			{ "data" : "f_sol"},
			{ "data" : "f_com"},
			{ "data" : "f_lib"},
			{ "data" : "f_can"}
			// { "data" : "per_espec"}
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
    	console.log(dataRow);
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
			$("#btn-aceptar-eval").hide();
			// $("#ft").show();
			setValoresFormulario( $(this), "#form-aceptar" );
		}
		$("#btn-guardar-eval").show();
		openModalTitle('#modal-evaluacion', '#modal-head-title', "Modificar Evaluación");

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
				tableEvaluaciones.ajax.reload( function(){ $( '#modal-loader' ).modal( 'hide' ); } );
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
				tableEvaluaciones.ajax.reload(function(){ $( '#modal-loader' ).modal( 'hide' ); });
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
			// console.log(response);
			if( response === true ){
				mensaje = "Se actualizo la evaluación.";
				clase = "alertify-success";
				$("#modal-evaluacion").modal('hide');
				tableEvaluaciones.ajax.reload(function(){ $( '#modal-loader' ).modal( 'hide' ); });
			}else{
				mensaje = "Ocurrio un error al actualizar.";
				clase = "alertify-danger";
			}

			alertMessage(mensaje, clase);
		});

	}

	//ACEPTAR EVALUACION
	$("#table-evaluaciones").on ('click', '.aceptar-eval', function(){  
		setValoresFormulario( $(this), "#form-aceptar" );
		setValoresFormulario( $(this), "#evaluacion" );
		disabledEdicionNuevo(true);
		showHideOpe("aceptar");
		$("#btn-aceptar-eval").show();
    	openModalTitle('#modal-evaluacion', '#modal-head-title', 'Aceptar Evaluación');
    	$("#form-aceptar").data('bootstrapValidator').resetForm();
	});
	
	$("#btn-aceptar-eval").click(function(){

		var validator = $('#form-aceptar').data('bootstrapValidator');
        validator.validate();
        validaCamposAdicionalesAceptar(['pv', 'ts', 'pr', 'fo']);
        if (validator.isValid()) {
            var data = {
        		id: $("#id").val(), 
        		ix: $("#ix").val(),
        		pv: $("#pv").val(), 
				ts: $("#ts").val(), 
				pr: $("#pr").val(), 
				fo: $("#fo").val(),
				fs: $("#fs").val(),
				sg: $("#sg").val(),
				ig: $("#ig").val(),
				te: $("#te").val(),
				nu: $("#nu").val(),
				pa: $("#pa").val(),
				me: $("#me").val()
				// ,meta: $("#meta").val()
        	}
			
			setPost(e.url + "aceptarEvaluacion", data, function(response){
				console.log(response);
				if( response.estatus === true ){
					mensaje = "Se aceptó la evaluación. " + response.mensaje;
					clase = "alertify-success";
					$("#modal-evaluacion").modal('hide');
					tableEvaluaciones.ajax.reload(function(){ $( '#modal-loader' ).modal( 'hide' ); });
				}else{
					mensaje = "Ocurrio un error al aceptar.";
					clase = "alertify-danger";
				}

				alertMessage(mensaje, clase);
			});
        }

	});


	//RECHAZAR EVALUACION
	$("#table-evaluaciones").on ('click', '.rechazar-eval', function(){  
		setValoresFormulario( $(this), "#form-rechazar" );
		setValoresFormulario( $(this), "#evaluacion" );
		disabledEdicionNuevo(true);
		showHideOpe("rechazar");
    	openModalTitle('#modal-evaluacion', '#modal-head-title', 'Rechazar Evaluación');
    	$("#form-rechazar").data('bootstrapValidator').resetForm();
	});
	
	$("#btn-rechazar-eval").click(function(){

		var validator = $('#form-rechazar').data('bootstrapValidator');
        validator.validate();
        // validaCamposAdicionalesAceptar(['pv', 'ts', 'pr', 'fo']);
        if (validator.isValid()) {

        	var data = {
        		id: $("#id").val(), 
        		ix: $("#ix").val(), 
        		mr: $("#motivo_rechazo").val(), 
				fr: $("#fecha_rechazo").val()
        	}
			
			setPost(e.url + "rechazarEvaluacion", data, function(response){
				//console.log(response);
				if( response === true ){
					mensaje = "Se rechazo la evaluación.";
					clase = "alertify-success";
					$("#modal-evaluacion").modal('hide');
					tableEvaluaciones.ajax.reload(function(){ $( '#modal-loader' ).modal( 'hide' ); });
				}else{
					mensaje = "Ocurrio un error al rechazar.";
					clase = "alertify-danger";
				}

				alertMessage(mensaje, clase);
			});

        }

	});



	//REENVIAR EVALUACION
	$("#table-evaluaciones").on ('click', '.reenviar-eval', function(){ 
		setValoresFormulario( $(this), "#form-reenviar" );
		setValoresFormulario( $(this), "#evaluacion" );
		showHideOpe("reenviar");
    	openModalTitle('#modal-evaluacion', '#modal-head-title', 'Reenviar Evaluación');
	});

	$("#btn-reenviar-eval").click(function(){
		var serial = $("form#evaluacion").serialize();
        setPost(e.url + "reenviarEvaluacion", serial, function(response){
			//console.log(response);
			if( response === true ){
				mensaje = "Se reenvio la evaluación estatus: En Proceso.";
				clase = "alertify-success";
				$("#modal-evaluacion").modal('hide');
				tableEvaluaciones.ajax.reload(function(){ $( '#modal-loader' ).modal( 'hide' ); });
			}else{
				mensaje = "Ocurrio un error al reenviar.";
				clase = "alertify-danger";
			}
			alertMessage(mensaje, clase);
		});

	});



	//LIBERAR EVALUACION
	$("#table-evaluaciones").on ('click', '.liberar-eval', function(){ 
		setValoresFormulario( $(this), "#form-liberar" );
		setValoresFormulario( $(this), "#evaluacion" );
		showHideOpe("liberar");
		$("#form-liberar").data('bootstrapValidator').resetForm();
    	openModalTitle('#modal-evaluacion', '#modal-head-title', 'Liberar Evaluación');
	});

	$("#btn-liberar-eval").click(function(){

		var validator = $('#form-liberar').data('bootstrapValidator');
        validator.validate();
        if (!validator.isValid())
        	return false;

        var data = {
    		id: $("#id").val(),
    		ix: $("#ix").val(), 
    		re: $("#resultado_lib").val(), 
			fl: $("#fecha_liberacion").val(),
			rl: $("#ref_liberacion").val()
    	}

        setPost(e.url + "liberarEvaluacion", data, function(response){
			// console.log(response);
			if( response === true ){
				mensaje = "Se Liberó la evaluación estatus: Liberada.";
				clase = "alertify-success";
				$("#modal-evaluacion").modal('hide');
				tableEvaluaciones.ajax.reload(function(){ $( '#modal-loader' ).modal( 'hide' ); });
			}else{
				mensaje = "Ocurrio un error al Liberar.";
				clase = "alertify-danger";
			}
			alertMessage(mensaje, clase);
		});

	});


	//CANCELAR EVALUACION
	$("#table-evaluaciones").on ('click', '.cancelar-eval', function(){ 
		setValoresFormulario( $(this), "#form-cancelar" );
		setValoresFormulario( $(this), "#evaluacion" );
		showHideOpe("cancelar");
		$("#form-cancelar").data('bootstrapValidator').resetForm();
    	openModalTitle('#modal-evaluacion', '#modal-head-title', 'Cancelar Evaluación');
	});

	$("#btn-cancelar-eval").click(function(){

		var validator = $('#form-cancelar').data('bootstrapValidator');
        validator.validate();
        if (!validator.isValid())
        	return false;

        var data = {
    		id: $("#id").val(), 
    		ix: $("#ix").val(), 
			fl: $("#fecha_cancelacion").val(), 
			fc: $("#fecha_cancelacion").val(), 
			mc: $("#motivo_cancelacion").val(),
			rl: $("#ref_cancelacion").val()
    	}

        setPost(e.url + "cancelarEvaluacion", data, function(response){
			// console.log(response);
			if( response === true ){
				mensaje = "Se canceló la evaluación estatus: Cancelada.";
				clase = "alertify-success";
				$("#modal-evaluacion").modal('hide');
				tableEvaluaciones.ajax.reload(function(){ $( '#modal-loader' ).modal( 'hide' ); });
			}else{
				mensaje = "Ocurrio un error al cancelar.";
				clase = "alertify-danger";
			}
			alertMessage(mensaje, clase);
		});

	});






});