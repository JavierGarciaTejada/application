$(function(){

	var e = {
        url: path + "index.php/reportes/",
        ident: null
    }


    var generaTablaIntervalos = function(){

        var filtros = {
            proceso: ['a.et', '=', '619056264933549', 'string']
            // ,especial: ['a.especial', '=', 0]
        };
        var dataGet = { filtros: filtros };
        getJson(e.url + "Intervalos", dataGet, function(data){

            e.ident = data.ids;

        	rowsEquipos( data.equipos );
        	rowsMateriales( data.materiales );
        	rowsDocs( data.docs );
        	rowsFuncionalidad( data.func );
        	rowsCaract( data.caract );
        	rowsEspecificaciones( data.espec );
            rowsEspecial( data.especial );
            rowsProducto( data.producto );

            $.each(data.totales, function(i, v){
                $("#total-"+(i+1)).text(v);
            })

            $("#proceso-total").text( data.proceso.total );
            $("#nuevas-total").text( data.nuevas.total );

        });

    }

    var rowsEquipos = function( equipos ){

    	var total = [];
    	total['nue'] = 0;
    	total['act'] = 0;

    	$.each(equipos, function(i, v){	
    		$.each(v, function(ia, va){
    			var id = "equipos-" + i + "-" + ia;

    			$("#" + id).text(va);
    			total[i] += parseInt(va);
    		})
    	});

    	$("#equipos-nue-total").text( total['nue'] );
		$("#equipos-act-total").text( total['act'] );

    }

    var rowsMateriales = function( materiales ){

    	var total = [];
    	total['cidec'] = 0;
    	total['local'] = 0;

    	$.each(materiales, function(i, v){	
    		$.each(v, function(ia, va){
    			var id = "materiales-" + i + "-" + ia;

    			$("#" + id).text(va);
    			total[i] += parseInt(va);
    		})
    	});

    	$("#materiales-cidec-total").text( total['cidec'] );
		$("#materiales-local-total").text( total['local'] );

    }


    var rowsDocs = function( docs ){

    	var total = 0;

    	$.each(docs, function(i, v){	
    		var id = "docs-" + i;

			$("#" + id).text(v);
			total += parseInt(v);
    	});

		$("#docs-total").text( total );

    }


    var rowsFuncionalidad = function( func ){

    	var total = 0;

    	$.each(func, function(i, v){	
    		var id = "func-" + i;

			$("#" + id).text(v);
			total += parseInt(v);
    	});

		$("#func-total").text( total );

    }


    var rowsCaract = function( caract ){

    	var total = [];
    	total['nue'] = 0;
    	total['act'] = 0;

    	$.each(caract, function(i, v){	
    		$.each(v, function(ia, va){
    			var id = "caract-" + i + "-" + ia;

    			$("#" + id).text(va);
    			total[i] += parseInt(va);
    		})
    	});

    	$("#caract-nue-total").text( total['nue'] );
		$("#caract-act-total").text( total['act'] );

    }

    var rowsEspecificaciones = function( espec ){

    	var total = [];
    	total['nue'] = 0;
    	total['act'] = 0;

    	$.each(espec, function(i, v){	
    		$.each(v, function(ia, va){
    			var id = "espec-" + i + "-" + ia;

    			$("#" + id).text(va);
    			total[i] += parseInt(va);
    		})
    	});

    	$("#espec-nue-total").text( total['nue'] );
		$("#espec-act-total").text( total['act'] );

    }

    var rowsEspecial = function(especial){
        $("#especiales-1-5").text(especial['especiales-1-5']);
        $("#especiales-total").text(especial['especiales-1-5']);
    }

    var rowsProducto = function( nuevo ){

        var total = 0;

        $.each(nuevo, function(i, v){    
            var id = "nuevo-" + i;

            $("#" + id).text(v);
            total += parseInt(v);
        });

        $("#nuevo-total").text( total );

    }


    generaTablaIntervalos();

    $(".action").click(function(){

        var id = $(this).attr('id');
        var item = id.split('-');
        var ids = null;
        var title = $(this).parents('tr').attr('data-title');
        var diasVence = $(this).attr('data-max');

        
        ids = e.ident[id];

        if( ids.length <= 0 )
            return false;

        var dataGet = { ids: ids }; 
        getJson(e.url + "IntervalosDetalle", dataGet, function(res){
            console.log(id);
            $("#modal-head-title").text( title )
            $("#modal-intervalos").modal('show');

            
            var items = [];
            var count = 1;
            $.each(res.data, function(i, v){
                var tr = $("<tr>");
                tr.append( $("<td>").html(count) );
                tr.append( $("<td>").html(v.el).addClass('bg-success') );
                tr.append( $("<td>").html(v.no) );
                tr.append( $("<td>").html(v.s_lab) );
                tr.append( $("<td>").html(v.subgerente) );
                tr.append( $("<td>").html(v.fs).addClass('bg-info') );
                tr.append( $("<td>").html(v.dias_t).addClass('bg-warning') );

                if( diasVence == "especial" ){
                    var text = "N/A";
                    var bg = '';
                }else{
                    var diasParaVencer =  parseInt(diasVence) - parseInt(v.dias_t);
                    if( diasParaVencer > 0  ){
                        var text = diasParaVencer + " para vencer";
                        var bg = '';
                    }else{
                        var text = "vencido por " + Math.abs(diasParaVencer);
                        var bg = 'bg-danger1';
                    }
                }
                
                // var text = ( diasParaVencer > 0 ) ? diasParaVencer + " para vencer" : "vencido por " + Math.abs(diasParaVencer);
                tr.append( $("<td>").html(text).addClass(bg) );
                items.push(tr[0].outerHTML);

                count++;
            })

            $("#table-intervalos-detalle tbody").html( items.join('') );

        })

        
    })


    $(".action-total").click(function(){
        var id = $(this).attr('id');
        ids = e.ident[id];

        var title = $(this).parents('tr').attr('data-title');

        var i = [];
        var assing = [];
        $.each(ids, function(ind, v){
            i.push(v[0]);
            assing[''+v[0]] = v[1];
        })

        var dataGet = { ids: i }; 
        getJson(e.url + "IntervalosDetalle", dataGet, function(res){
            console.log(res);
            $("#modal-head-title").text( title )
            $("#modal-intervalos").modal('show');

            
            var items = [];
            var count = 1;
            $.each(res.data, function(i, v){
                var tr = $("<tr>");
                tr.append( $("<td>").html(count) );
                tr.append( $("<td>").html(v.el).addClass('bg-success') );
                tr.append( $("<td>").html(v.no) );
                tr.append( $("<td>").html(v.s_lab) );
                tr.append( $("<td>").html(v.subgerente) );
                tr.append( $("<td>").html(v.fs).addClass('bg-info') );
                tr.append( $("<td>").html(v.dias_t).addClass('bg-warning') );
                var diasVence = assing[v.id];
                var diasParaVencer = parseInt(diasVence) - parseInt(v.dias_t);
                if( diasParaVencer > 0  ){
                    var text = diasParaVencer + " para vencer";
                    var bg = '';
                }else{
                    var text = "vencido por " + Math.abs(diasParaVencer);
                    var bg = 'bg-danger1';
                }
                // var text = ( diasParaVencer > 0 ) ? diasParaVencer + " para vencer" : "vencido por " + Math.abs(diasParaVencer);
                tr.append( $("<td>").html(text).addClass(bg) );
                items.push(tr[0].outerHTML);
                count++;
            })

            $("#table-intervalos-detalle tbody").html( items.join('') );
        })
        
    })

})