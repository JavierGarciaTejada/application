$(function(){

    var e = {
        url: path + "index.php/mercado/"
    }

    var tableMercado = $( '#table-mercado' ).DataTable( 
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
                text: 'Nuevo Mercado',                
                action: function ( dt ) {
                    var title = "Nuevo Mercado";
                    $("#form-mercado")[0].reset(); //LIMPIA EL FORMULARIO DE REGISTRO
                    $("#ix, #id").val("");
                    openModalTitle('#modal-mercado', '#modal-head-title', title); //DESPLIEGA EL MODAL
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
                    botones.push('<button class="btn btn-sm btn-primary editar-mer" id='+ data.id +' title="Editar Mercado"><i class="fa fa-pencil" aria-hidden="true"></i></button>');
                    // botones.push('<button class="btn btn-sm btn-danger eliminar-user" id='+ data.id +' title="Eliminar Mercado"><i class="fa fa-trash" aria-hidden="true"></i></button>');
                    $( td ).html( '<div style="width: 110px;">' + botones.join(' ') + '</div>' );
                }
            },
            { "data" : "id"},
            { "data" : "no"}

        ]
    } );
    $("button.dt-button").addClass('btn btn-primary btn-sm');


    $("#table-mercado").on('click', '.editar-mer', function(){

        var dataRow = tableMercado.row( $(this).parents('tr') ).data();
        var title = "Modificar Mercado";
        $("#form-mercado")[0].reset();
        $.each(dataRow, function(i, v){
            $("#form-mercado #"+i).val(v);
        })
        openModalTitle('#modal-mercado', '#modal-head-title', title); //DESPLIEGA EL MODAL

    })

    $("#btn-guardar-mer").click(function(){

        var id = $("#id").val();
        var proveedor = $("#no").val();

        if( proveedor == "" ){
            alert('Ingrese mercado / Equipo');
            return false;
        }

        var serial = $("#form-mercado").serialize();
        if( id == "" ){
            $("#btn-guardar-mer").attr({disabled: true});
            registrarmercado(serial);
        }
        else{
            $("#btn-guardar-mer").attr({disabled: true});
            modificarmercado(serial);
        }

    })

    var registrarmercado = function(serial){
        setPost(e.url + "store", serial, function(response){
            //console.log(response);
            if( response === true ){
                mensaje = "Se registraron los datos.";
                clase = "alertify-success";
                $("#modal-mercado").modal('hide');
                tableMercado.ajax.reload( function(){ $( '#modal-loader' ).modal( 'hide' ); } );
            }else{
                mensaje = "Ocurrio un error al registrar.";
                clase = "alertify-danger";
            }

            alertMessage(mensaje, clase);
            $("#btn-guardar-mer").attr({disabled: false});
        });
    }


    var modificarmercado = function(serial){
        setPost(e.url + "update", serial, function(response){
            //console.log(response);
            if( response === true ){
                mensaje = "Se actualizaron los datos.";
                clase = "alertify-success";
                $("#modal-mercado").modal('hide');
                tableMercado.ajax.reload( function(){ $( '#modal-loader' ).modal( 'hide' ); } );
            }else{
                mensaje = "Ocurrio un error al actualizar.";
                clase = "alertify-danger";
            }

            alertMessage(mensaje, clase);
            $("#btn-guardar-mer").attr({disabled: false});
        });
    }


})