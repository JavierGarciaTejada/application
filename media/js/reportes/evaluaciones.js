$(function(){

	var e = {
        url: path + "index.php/reporteEvaluacion/",
        ident: null
    }


    var getEvaluacionesProceso = function(){

        var filtros = {
            proceso: ['a.fl', '=', '0000-00-00 00:00:00', 'string']
            // ,especial: ['a.especial', '=', 0]
        };
        var dataGet = { filtros: filtros };
        getJson(e.url + "EvaluacionesProceso", dataGet, function(data){

            $.each(data.table, function(i, v){

                var items = [];
                $.each(v, function(ind, val){
                    var tr = $("<tr>");
                    tr.append( $("<td>").html(ind) )
                    tr.append( $("<td>").html( v[ind]['data'].length ) )
                    var descripcion = "";
                    $.each(v[ind]['desc'], function(idesc, vdesc){
                        descripcion += idesc +" "+ vdesc.length + ", ";
                    })
                    tr.append( $("<td>").html( descripcion.substring(0, (descripcion.length - 2) ) ) )
                    $("#table-"+i+" tbody").append( tr[0].outerHTML );
                })
                
                
            })

            $("#total_general").text( data.total_general )
            $("#total_nuevo").text( data.total_nuevo )
            $("#total_existente").text( data.total_existente )

        });

    }


    var getEvaluacionesSolicitante = function(){
        var filtros = {
            proceso: ['a.fl', '=', '0000-00-00 00:00:00', 'string'],
            nuevo: ['a.pd', '=', '1', '']
        };
        var dataGet = { filtros: filtros };
        getJson(e.url + "EvaluacionesSolicitante", dataGet, function(data){

            $.each(data.table, function(i, v){

                var items = [];
                var sumaSolicitante = 0;
                $.each(v, function(ind, val){
                    
                    $.each(val, function(inda, vala){
                        var tr = $("<tr>");
                        tr.append( $("<td>").html(i) );
                        tr.append( $("<td>").html(ind) );
                        tr.append( $("<td>").html(inda) );
                        sumaSolicitante += val[inda].length;
                        tr.append( $("<td>").html( val[inda].length ).addClass('text-center') )
                        $("#table-solicitante tbody").append( tr[0].outerHTML );
                    })
                    
                })
                $("#table-solicitante tbody").append( "<tr class='text-center bg-warning'> <td colspan='3'></td> <td colspan=''> "+sumaSolicitante+"</td> </tr>" );
                
                
            })

        });
    }


    var getEvaluacionesGeneral = function(){

        var buttonsEval = [
            'excel'
        ];

        var filtros = {
            proceso: ['a.fl', '=', '0000-00-00 00:00:00', 'string']
            // ,especial: ['a.especial', '=', 0]
        };
        var dataGet = { filtros: filtros };
        getJson(e.url + "EvaluacionesGeneral", dataGet, function(data){

            var item = [];
            var ind = 1;
            var nuevos = true;
            $.each(data.data, function(i, v){

                var bg = ( parseInt(v.pd) === 1  ) ? "#d4fed3" : "#fff";

                var tr = $("<tr>").attr({'bgcolor' : bg});
                tr.append( $("<td>").html(v.indice) )
                tr.append( $("<td>").html(v.proyecto_asociado) )
                tr.append( $("<td>").html(v.mercado) )
                tr.append( $("<td>").html(v.no) )
                tr.append( $("<td>").html(v.proveedor) )
                tr.append( $("<td>").html(v.etapa) )
                tr.append( $("<td>").html(v.f_com) )
                tr.append( $("<td>").html(v.ob) )
                tr.append( $("<td>").html(v.solicitante_sub) )
                tr.append( $("<td>").html(v.producto) )
                $("#table-general tbody").append(tr[0].outerHTML)
                item.push( tr[0].outerHTML );
                ind++;
                
            })

            $("#table-general tbody").html( item.join('') );
            var tableGeneral = $("#table-general").DataTable({
                "processing" : true,
                "language" : lenguageTable,
                dom: 'Bfrtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ]
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                ]
                // ,"paging": false
            });
            // tableGeneral.buttons().container().appendTo( '#example_wrapper .col-sm-6:eq(0)' );
            // $("button.dt-button").addClass('btn btn-primary btn-sm');

        });

    }

    getEvaluacionesProceso();
    getEvaluacionesSolicitante();
    getEvaluacionesGeneral();



})