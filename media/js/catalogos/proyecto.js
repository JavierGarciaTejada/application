$(function(){

    var e = {
        url: path + "index.php/proyecto/"
    }

    var tableproyecto = $( '#table-proyecto' ).DataTable( 
    {
        "processing" : true,
        "language" : lenguageTable,
        "scrollX" : true,
        "scrollY" : '62vh',
        "scrollCollapse" : true,
        "orderCellsTop": true,
        "fixedHeader": true,
        "ajax" : {
            "url" : e.url + "show",
            "type" : "GET"
        },
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: [
            'excel',
            {
                text: 'Nuevo Proyecto Asociado',
                action: function ( dt ) {
                    var title = "Nuevo Proyecto Asociado";
                    $("#form-proyecto")[0].reset(); //LIMPIA EL FORMULARIO DE REGISTRO
                    $("#ix, #id").val("");
                    openModalTitle('#modal-proyecto', '#modal-head-title', title); //DESPLIEGA EL MODAL
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
                    botones.push('<button class="btn btn-sm btn-primary editar-pro" id='+ data.id +' title="Editar Proyecto Asociado"><i class="fa fa-pencil" aria-hidden="true"></i></button>');
                    // botones.push('<button class="btn btn-sm btn-danger eliminar-user" id='+ data.id +' title="Eliminar pronología/equipo"><i class="fa fa-trash" aria-hidden="true"></i></button>');
                    $( td ).html( '<div style="width: 110px;">' + botones.join(' ') + '</div>' );
                }
            },
            { "data" : "id"},
            { "data" : "no"}

        ]
    } );
    $("button.dt-button").addClass('btn btn-primary btn-sm');


    $("#table-proyecto").on('click', '.editar-pro', function(){

        var dataRow = tableproyecto.row( $(this).parents('tr') ).data();
        var title = "Modificar Proyecto Asociado";
        $("#form-proyecto")[0].reset();
        $.each(dataRow, function(i, v){
            $("#form-proyecto #"+i).val(v);
        })
        openModalTitle('#modal-proyecto', '#modal-head-title', title); //DESPLIEGA EL MODAL

    })

    $("#btn-guardar-pro").click(function(){

        var id = $("#id").val();
        var proveedor = $("#no").val();

        if( proveedor == "" ){
            alert('Ingrese Proyecto Asociado');
            return false;
        }

        var serial = $("#form-proyecto").serialize();
        if( id == "" ){
            $("#btn-guardar-pro").attr({disabled: true});
            registrarproyecto(serial);
        }
        else{
            $("#btn-guardar-pro").attr({disabled: true});
            modificarproyecto(serial);
        }

    })

    var registrarproyecto = function(serial){
        setPost(e.url + "store", serial, function(response){
            //console.log(response);
            if( response === true ){
                mensaje = "Se registraron los datos.";
                clase = "alertify-success";
                $("#modal-proyecto").modal('hide');
                tableproyecto.ajax.reload( function(){ $( '#modal-loader' ).modal( 'hide' ); } );
            }else{
                mensaje = "Ocurrio un error al registrar.";
                clase = "alertify-danger";
            }

            alertMessage(mensaje, clase);
            $("#btn-guardar-pro").attr({disabled: false});
        });
    }


    var modificarproyecto = function(serial){
        setPost(e.url + "update", serial, function(response){
            //console.log(response);
            if( response === true ){
                mensaje = "Se actualizaron los datos.";
                clase = "alertify-success";
                $("#modal-proyecto").modal('hide');
                tableproyecto.ajax.reload( function(){ $( '#modal-loader' ).modal( 'hide' ); } );
            }else{
                mensaje = "Ocurrio un error al actualizar.";
                clase = "alertify-danger";
            }

            alertMessage(mensaje, clase);
            $("#btn-guardar-pro").attr({disabled: false});
        });
    }


})