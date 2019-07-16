$(function(){

	var e = {
        url: path + "index.php/proveedores/"
    }

     var tableProveedores = $( '#table-proveedores' ).DataTable( 
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
                text: 'Nuevo Proveedor',
                action: function ( dt ) {
                    var title = "Nuevo Proveedor";
                    $("#form-proveedores")[0].reset(); //LIMPIA EL FORMULARIO DE REGISTRO
                    $("#ix, #id").val("");
                    // $("#form-proveedores").data('bootstrapValidator').resetForm();
                    openModalTitle('#modal-proveedores', '#modal-head-title', title); //DESPLIEGA EL MODAL
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
                    botones.push('<button class="btn btn-sm btn-primary editar-prov" id='+ data.id +' title="Editar Proveedor"><i class="fa fa-pencil" aria-hidden="true"></i></button>');
                    // botones.push('<button class="btn btn-sm btn-danger eliminar-user" id='+ data.id +' title="Eliminar Proveedor"><i class="fa fa-trash" aria-hidden="true"></i></button>');
                    $( td ).html( '<div style="width: 110px;">' + botones.join(' ') + '</div>' );
                }
            },
            { "data" : "id"},
            { "data" : "no"}

        ]
    } );
    $("button.dt-button").addClass('btn btn-primary btn-sm');

})