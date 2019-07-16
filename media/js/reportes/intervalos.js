$(function(){

	var e = {
        url: path + "index.php/reportes/",
        ident: null
    }


    var generaTablaIntervalos = function(filtros = ""){

        var dataGet = { filtros: filtros }; 
        getJson(e.url + "Intervalos", dataGet, function(data){

            e.ident = data.ids;

        	rowsEquipos( data.equipos );
        	rowsMateriales( data.materiales );
        	rowsDocs( data.docs );
        	rowsFuncionalidad( data.func );
        	rowsCaract( data.caract );
        	rowsEspecificaciones( data.espec );

            $.each(data.totales, function(i, v){
                $("#total-"+i).text(v);
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


    generaTablaIntervalos();
    // $("#table-intervalos").DataTable();


    $(".action").click(function(){

        var id = $(this).attr('id');
        var item = id.split('-');
        var ids = null;

        
        ids = e.ident[id];

        if( ids.length <= 0 )
            return false;

        var dataGet = { ids: ids }; 
        getJson(e.url + "IntervalosDetalle", dataGet, function(res){
            console.log(id);
            $("#modal-head-title").text( id )
            $("#modal-intervalos").modal('show');

            
            var items = [];
            $.each(res.data, function(i, v){
                var tr = $("<tr>");
                tr.append( $("<td>").html(v.el).addClass('bg-success') );
                tr.append( $("<td>").html(v.no) );
                tr.append( $("<td>").html(v.s_lab) );
                tr.append( $("<td>").html(v.fs).addClass('bg-info') );
                tr.append( $("<td>").html(v.dias_t).addClass('bg-warning') );
                items.push(tr[0].outerHTML);
            })

            $("#table-intervalos-detalle tbody").html( items.join('') );
        })

        
    })

})