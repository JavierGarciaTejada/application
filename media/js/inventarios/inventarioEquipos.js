$(function(){
	
	var e = {
		url: path + "index.php/InventarioEquipos/",
		tecItems: null
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
		var r = ( $(this).val() == "PROCESAMIENTO" ) ? 3 : 0;
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


	//BTN SELECCIONAR TECNOLOGIA 
	$("#btn-tecnologia").click(function(){

		e.tecItems = $("#tecnologia").val().split(',');
		getJson(e.url + "getTecnologia", null, function(a){
			var items = [];
			$.each(a.data, function(i, v){
				var chk = ( e.tecItems.includes(v.no) ) ? 'checked' : '';
				items.push( '<div class="col-sm-3"><label><input type="checkbox" class="chk_tec" '+chk+' id="chk_'+v.no+'" value="'+v.no+'"> '+v.no+'</label></div>' );
			})
			$("#form-inv-tecnologia").html(items.join(''));
		});
		$("#modal-inv-tecnologia").modal('show');

	});

	$("#btn-selec-tec").click(function(){
		e.tecItems = []
		$(".chk_tec").each(function(i, v){
			if( $(this).is(":checked") )
				e.tecItems.push($(this).val())
		})
		$("#tecnologia").val( e.tecItems.join(',') );
		$("#modal-inv-tecnologia").modal('hide');
	})



	$('#table-inventario thead tr').clone(true).appendTo( '#table-inventario thead' );
    $('#table-inventario thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control input-sm" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( tableInventario.column(i).search() !== this.value ) {
                tableInventario
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
        // "searching": false,
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
					if( data.gerencia == $("#siglas").val().toUpperCase() || $("#_id").val() == '146')
						botones.push( '<button class="btn btn-sm btn-info modificar-inv" id='+ data.id +' title="Actualizar Registro"><i class="fa fa-edit" aria-hidden="true"></i></button>' );
					$( td ).html( '' + botones.join(' ') + '' );
				}
			},
			{ "data" : "red"},
			{ "data" : "equipo"},
			{ "data" : "clasificacion_cmdb"},
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
			{ "data" : "cons_actualizar"},
			{ "data" : "lineas"},
			{ "data" : "usuarios"},
			{ "data" : "troncales"},
			{ "data" : "equipos_gestionan"},
			{ "data" : "version_eq_gestion"},

			{ "data" : "estado_vigencia"},
			{ "data" : "ciclo_anios"},
			{ "data" : "inicio_contrato"},
			{ "data" : "vigencia_contrato"},
			{ "data" : "estatus_del_contrato"},

			{ "data" : "observaciones"}
			
		]
	} );
	tableInventario.buttons().container().appendTo( '#example_wrapper .col-sm-6:eq(0)' );
	$("button.dt-button").addClass('btn btn-primary btn-sm');



	$("#btn-guardar-inv").click(function(){
		var validator = $('#form-inv-equipos').data('bootstrapValidator');
        validator.validate();
        if (!validator.isValid())
			return false;

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
		$("#form-inv-equipos").data('bootstrapValidator').resetForm();
		$("#form-inv-equipos")[0].reset();
		setValoresFormulario( $(this), "#form-inv-equipos" );
		$("#modal-inv-equipos").modal('show');
	})



});