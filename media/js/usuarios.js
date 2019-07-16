
$(function(){

    var e = {
        url: path + "index.php/Usuarios/"
    }

    var setSiglas = function(tipo){
        var filtros = { siglas: ['rx', '=', tipo, 'int'] };
        var dataPost = { filtros: filtros };
        getJson(e.url + "siglas", null, function(a){
            setValuesSelect('cl', a.data, 'ix', 'cl', 'rx');
        });
    }

    var setPuestos = function(){
        getJson(e.url + "puestos", null, function(a){
            setValuesSelect('pu', a.data, 'ix', 'no', '');
        });
    }

    var setPerfil = function(){
        getJson(e.url + "perfiles", null, function(a){
            setValuesSelect('lr', a.data, 'ix', 'no', '');
        });
    }

    //SETEA LOS DATOS EN EL FORMUALRIO DEL MODAL
    var setValoresFormulario = function(row, formId){
        $(formId)[0].reset();
        var dataRow = tableUsuarios.row( row.parents('tr') ).data();
        $.each(dataRow, function(i, v){
            $(formId +" #"+i).val(v);
        })
    }

    setPuestos();
    setPerfil();
    setSiglas();

    var tableUsuarios = $( '#table-usuarios' ).DataTable( 
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
                text: 'Nuevo Usuario',
                action: function ( dt ) {
                    var title = "Nuevo Usuario";
                    $("#form-usuarios")[0].reset(); //LIMPIA EL FORMULARIO DE REGISTRO
                    $("#ix, #id").val("");
                    $(".inp-nuevo-editar").attr({disabled: true});
                    $("#form-usuarios").data('bootstrapValidator').resetForm();
                    openModalTitle('#modal-usuarios', '#modal-head-title', title); //DESPLIEGA EL MODAL
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
                    botones.push('<button class="btn btn-sm btn-primary editar-user" id='+ data.id +' title="Editar Usuario"><i class="fa fa-pencil" aria-hidden="true"></i></button>');
                    // botones.push('<button class="btn btn-sm btn-danger eliminar-user" id='+ data.id +' title="Eliminar Usuario"><i class="fa fa-trash" aria-hidden="true"></i></button>');
                    $( td ).html( '<div style="width: 110px;">' + botones.join(' ') + '</div>' );
                }
            },
            { "data" : "id"},
            { "data" : "siglas"},
            { "data" : "no"},
            { "data" : "ap"},
            { "data" : "am"},
            { "data" : "ni"},
            { "data" : "puesto"}
        ]
    } );
    $("button.dt-button").addClass('btn btn-primary btn-sm');


    var muestraSiglasArea = function(tipo){
        $('#cl > option').each(function() {
            if( tipo != $(this).attr('data-ref') )
                $(this).hide();
            else
               $(this).show();
        });
    }


    $("#tipo_user").change(function(){
        var val = $(this).val()
        if( val == "" ){
            $(".inp-nuevo-editar").attr({disabled: true});
            return false;
        }

        $(".inp-nuevo-editar").attr({disabled: false});
        muestraSiglasArea(val);
    })


    $("#table-usuarios").on('click', '.editar-user', function(){

        $("#form-usuarios").data('bootstrapValidator').resetForm();
        $(".inp-nuevo-editar").attr({disabled: false});
        var dataRow = tableUsuarios.row( $(this).parents('tr') ).data();
        var tipo = dataRow.gcl == "" ? "1" : "0";
        muestraSiglasArea(tipo);
        dataRow.tipo_user = tipo;
        openModalTitle('#modal-usuarios', '#modal-head-title', "Modificar Usuario");
        setValoresFormulario( $(this), "#form-usuarios" );

    })

    $("#btn-guardar-user").click(function(){

        var validator = $('#form-usuarios').data('bootstrapValidator');
        validator.validate();
        if (!validator.isValid())
            return false;

        if( $("#id").val() == "" )
            registraUsuario();
        else
            modificaUsuario();

    })

    var registraUsuario = function(){
        var serial = $("#form-usuarios").serialize();
        setPost(e.url + "store", serial, function(response){
            if( response === true ){
                mensaje = "Usuario registrado.";
                clase = "alertify-success";
                $("#modal-usuarios").modal('hide');
                tableUsuarios.ajax.reload();
            }else{
                mensaje = "Ocurrio un error al registrar usuario.";
                clase = "alertify-danger";
            }
            alertMessage(mensaje, clase);
        });
    }

    var modificaUsuario = function(){

        var serial = $("#form-usuarios").serialize();
        setPost(e.url + "update", serial, function(response){
            if( response === true ){
                mensaje = "Se actualizaron los datos.";
                clase = "alertify-success";
                $("#modal-usuarios").modal('hide');
                tableUsuarios.ajax.reload();
            }else{
                mensaje = "Ocurrio un error al actualizar.";
                clase = "alertify-danger";
            }
            alertMessage(mensaje, clase);
        });

    }


})



