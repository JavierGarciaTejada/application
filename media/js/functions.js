var dir = window.location.pathname;
var datHost = window.location;
var protocol = (window.location.protocol || "http:");
var host = (window.location.host || "");
var hostName = (window.location.hostname || "");
var origin = (window.location.origin || "");
var port = (window.location.port || "");
//var path = (protocol +"//"+ host + dir);
//var path = dir.replace(/\\/g, '/');
var lenguageTable = {
	"decimal":        "",
	"emptyTable":     "No hay datos disponibles en la tabla",
	"info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
	"infoEmpty":      "Mostrando 0 a 0 de 0 registros",
	"sInfoFiltered":   "(Filtrado de _MAX_ total de registros)",
	"infoPostFix":    "",
	"thousands":      ",",
	"lengthMenu":     "Ver _MENU_ registros",
	"loadingRecords": "Cargando...",
	"processing":     "Procesando...",
	"search":         "Buscar:",
	"zeroRecords":    "No se encontro ninguna coincidencia",
	"paginate": {
		"first":      "Primero",
		"last":       "Ultimo",
		"next":       "Siguiente",
		"previous":   "Anterior"
	},
	"aria": {
		"sortAscending":  ": Activar para ordenar la columna ascendentemente",
		"sortDescending": ": Activar para ordenar la columna descendente"
	}
};

/*Variable para cambiar idioma Date Picker Jquery UI*/
var closeText = 'Cerrar';
var prevText = 'Anterior';
var nextText = 'Siguiente';
var currentText = 'Hoy';
var monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
var monthNamesShort = ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'];
var dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
var dayNamesShort = ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'];
var dayNamesMin = ['Do','Lu','Ma','Mi','Ju','Vi','Sá'];
var weekHeader = 'Sm';
/* ************************* */

function setPost( url, params, callback ) {
	$.post( url, params )
	.done( function( result ) {
		// console.log(result);
		callback( result );
	})
	.fail( function( jqxhr, textStatus, error ) {
		// console.log(error);
		var err = textStatus +', '+ error;
		callback( err );
	} );
}

function getJson( url, params, callback ) {
	$.getJSON( url, params )
	.done( function( json ) {
		callback( json );
	} )
	.fail( function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error;
		callback( err );
	} );
}

function ajaxDinamic( method, url, type, data, timeOut, varBeforeSend, callback, contentType, processData, async, cache ) {
	$.ajax({
		method: method,
		url: url,
		dataType: type,
		data: data,
		contentType: ( contentType ? contentType : false ),
		processData: ( processData ? processData : false ),
		async: ( async ? async : false ),
		cache: ( cache ? cache : false ),
		timeout: ( timeOut ? timeOut : 10000 ),
		statusCode: {
			404: function() {
				alert( "page not found" );
			}
		},
		beforeSend: function() {
			varBeforeSend;
		}
	}).done(function( value ) {
		callback( value );
	}).fail( function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error;
		callback( { err: true, message: "Error!: "+ err } );
	});
}

function alertMessage(message, clase) {
	$( "#notificacion-mensaje" ).append( '<span class="'+clase+'">'+message+'</span>' );
	$( "."+clase ).delay( 5000 ).slideUp( "slow", function() {
		$( this ).remove();
	} );
}

function stataticMessage(message, clase) {
	$( "#static-mensaje" ).append( '<div class="'+clase+'"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span><strong><center>'+message+'</center></strong></div>');
}

function recuperaValorSelect(class_id) 
{
	var attrib = $( class_id ).attr( 'rec_val' );

	if( attrib != "" ) {
		setTimeout(function() {
			$( 'select' ).find( 'option' ).each( function () {
				
				var valLid = $( this ).val();
				//console.log( valLid );
				if (valLid==attrib)
				{
					$( this ).attr("selected", "selected");
				}
			});
		}, 1000);	
	}
}

function resizeBody() {
	var documentHeight = $( document ).height();
	var screenHeight = $( window ).height();
	var bodyHeight = ( $( '#div-body' ).length ? $( '#div-body' ).innerHeight() : $( '#body-layout').innerHeight() );
	var realBodyHeight = ( $( '#div-body' ).length ? $( '#div-body' ).height() : $( '#body-layout').height() );
	var footerHeight = $( '#div-footer' ).innerHeight();

	if( bodyHeight < screenHeight && documentHeight <= screenHeight ) {
		var bodyHe = screenHeight - ( footerHeight + bodyHeight + 16 );
		var heightBody = bodyHe + realBodyHeight;
		( ($( '#div-body' ).length) ? $( '#div-body' ).height( heightBody ) : $( '#body-layout' ).height( ( heightBody + 70 ) ) );
	} else if( documentHeight > screenHeight ) {
		$( '#div-body, #body-layout' ).css( 'height', 'auto' );
	}
}


function setValuesSelect(idSelect, data, keyValue, keyText, keyRef){

	if( data.length <= 0 ){
		$( "#"+idSelect ).empty();
		$( "#"+idSelect ).html('<option value="">Seleccionar</option>');
		$( "#"+idSelect ).attr({'disabled': true});
		return false;
	}

	$( "#"+idSelect ).empty();
	var options = ['<option value="">Seleccionar</option>'];
	var item = "";

	$.each(data, function(ind, val) {
		var ref = keyRef !== "" ? " data-ref='"+val[keyRef]+"' " : "";
		if( val[keyText] !== "" ){
			item = '<option value="'+val[keyValue]+'" '+ref+'>'+val[keyText]+'</option>';
			options.push(item);
		}
	})
	$( "#"+idSelect ).html( options.join('') );
 
}

function openModalTitle(idModal, idTitle, titleHead){
	$(idModal + " " + idTitle).html(titleHead);
    $(idModal).modal('show');
}

function validaCamposAdicionalesAceptar(adicionales){

	if (adicionales.length === 0)
		return false;

    $.each(adicionales, function(i, v){
        var element = $("#" + v);
        if( element.val() == "" ){

            var label = $("label[for='"+v+"']");
            alert("Verifique "+label.text());

            var div = element.parent('div');
            div.addClass('has-error');
            return false;
        }
    })
    return true;

}