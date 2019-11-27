$(function(){

	var e = {
        url: path + "index.php/ReporteInventario/",
        ident: null
    }


    var generaTablaInventario = function(){

        var filtros = {
            proceso: ['a.et', '=', '619056264933549', 'string']
            // ,especial: ['a.especial', '=', 0]
        };
        var dataGet = { filtros: filtros };
        getJson(e.url + "InventarioRed", null, function(data){

            var items = [];
            var totalGeneral = 0;
            $.each(data.encabezado.data, function(i, v){
                totalGeneral += parseInt(v.total);
                console.log(totalGeneral);
                items.push( "<tr><th>"+v.red+"</th><th>"+new Intl.NumberFormat().format(v.total)+"</th></tr>" );
                $.each(data.cuerpo.data[v.red], function(ind, vl){
                    var tr = $('<tr>');
                    tr.append( $("<td>").html( vl.equipo ) );
                    tr.append( $("<td>").html( vl.conteo ) );
                    items.push( tr[0].outerHTML );
                }) 
            })

            $("#table-reporte-inventario").html(items.join(''));
            $("#table-reporte-inventario").append("<tr><th>TOTAL GENERAL</th><th>"+new Intl.NumberFormat().format(totalGeneral)+"</th></tr>");
            $("#table-reporte-inventario").addClass('table table-condensed');

        });

    }


    generaTablaInventario();


})