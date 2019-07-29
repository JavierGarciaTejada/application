$(function(){

    var e = {
        url: path + "index.php/tecnologia/"
    }

    var tableTecnologia = $( '#table-tecnologia' ).DataTable( 
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
                text: 'Nuevo Tecnología/equipo',
                action: function ( dt ) {
                    var title = "Nuevo Tecnología/equipo";
                    $("#form-tecnologia")[0].reset(); //LIMPIA EL FORMULARIO DE REGISTRO
                    $("#ix, #id").val("");
                    openModalTitle('#modal-tecnologia', '#modal-head-title', title); //DESPLIEGA EL MODAL
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
                    botones.push('<button class="btn btn-sm btn-primary editar-tec" id='+ data.id +' title="Editar Tecnología/equipo"><i class="fa fa-pencil" aria-hidden="true"></i></button>');
                    // botones.push('<button class="btn btn-sm btn-danger eliminar-user" id='+ data.id +' title="Eliminar Tecnología/equipo"><i class="fa fa-trash" aria-hidden="true"></i></button>');
                    $( td ).html( '<div style="width: 110px;">' + botones.join(' ') + '</div>' );
                }
            },
            { "data" : "id"},
            { "data" : "no"}

        ]
    } );
    $("button.dt-button").addClass('btn btn-primary btn-sm');


    $("#table-tecnologia").on('click', '.editar-tec', function(){

        var dataRow = tableTecnologia.row( $(this).parents('tr') ).data();
        var title = "Modificar Tecnología / Equipo";
        $("#form-tecnologia")[0].reset();
        $.each(dataRow, function(i, v){
            $("#form-tecnologia #"+i).val(v);
        })
        openModalTitle('#modal-tecnologia', '#modal-head-title', title); //DESPLIEGA EL MODAL

    })

    $("#btn-guardar-tec").click(function(){

        var id = $("#id").val();
        var proveedor = $("#no").val();

        if( proveedor == "" ){
            alert('Ingrese tecnologia / Equipo');
            return false;
        }

        var serial = $("#form-tecnologia").serialize();
        if( id == "" ){
            $("#btn-guardar-tec").attr({disabled: true});
            registrarTecnologia(serial);
        }
        else{
            $("#btn-guardar-tec").attr({disabled: true});
            modificarTecnologia(serial);
        }

    })

    var registrarTecnologia = function(serial){
        setPost(e.url + "store", serial, function(response){
            //console.log(response);
            if( response === true ){
                mensaje = "Se registraron los datos.";
                clase = "alertify-success";
                $("#modal-tecnologia").modal('hide');
                tableTecnologia.ajax.reload( function(){ $( '#modal-loader' ).modal( 'hide' ); } );
            }else{
                mensaje = "Ocurrio un error al registrar.";
                clase = "alertify-danger";
            }

            alertMessage(mensaje, clase);
            $("#btn-guardar-tec").attr({disabled: false});
        });
    }


    var modificarTecnologia = function(serial){
        setPost(e.url + "update", serial, function(response){
            //console.log(response);
            if( response === true ){
                mensaje = "Se actualizaron los datos.";
                clase = "alertify-success";
                $("#modal-tecnologia").modal('hide');
                tableTecnologia.ajax.reload( function(){ $( '#modal-loader' ).modal( 'hide' ); } );
            }else{
                mensaje = "Ocurrio un error al actualizar.";
                clase = "alertify-danger";
            }

            alertMessage(mensaje, clase);
            $("#btn-guardar-tec").attr({disabled: false});
        });
    }


})