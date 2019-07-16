function exportAjax( dir, data_form, form ) {
	if( (navigator.userAgent.indexOf("MSIE ") > -1) === true || !!navigator.userAgent.match(/Trident.*rv\:11\./) ) {
		var dat_f = data_form.split("&");
		var html_fo = "";
		$.each( dat_f, function( i, val ) {
			var value = val.split( '=' );
			html_fo += '<input type="hidden" name="'+ value[0] +'" value="'+ value[1] +'">';
		} );
		$( '#form-hide' ).html( html_fo );
		$( '#form-hide' ).attr( "action", dir );
		$( '#form-hide' ).submit();
		return false;
	} else {
		form.parents( 'div.modal-export' ).one( 'hidden.bs.modal', function() {
			$( '#modal-loader' ).modal( 'show' );
		} ).modal( 'hide' );
		
		$.ajax( {
			url: dir,
			method: 'POST',
			data : data_form,
			xhrFields: {
				responseType: 'blob'
			},
			success: function ( response, textStatus, jqXHR ) {
				var name_file = (((jqXHR.getResponseHeader( 'Content-Disposition' )).split( ";" ))[1]).split("=")[1];
				$( '#modal-loader' ).modal( 'hide' );
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL( response );
				link.download = name_file;
				document.body.appendChild( link );
				link.click();
				document.body.removeChild(link);
				alertMessage( "Descargando archivo \""+ name_file +"\"...", "alertify-success" );
			}
		} ).fail( function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			alertMessage( "Error!: "+ err, "alertify-danger" );
			$( '#modal-loader' ).modal( 'hide' );
		});
	}
}

$( function() {
	if( $( '.form-val-bit' ).length ) {
		$( '.form-val-bit' ).bootstrapValidator( {
			message : 'Este valor no es valido',
			feedbackIcons: {
				valid : 'glyphicon glyphicon-ok',
				invalid : 'glyphicon glyphicon-remove',
				validating : 'glyphicon glyphicon-refresh'
			},
			fields : {
				connect_instance : {
					validators : {
						notEmpty : {
							message : 'Selecciona una opción no puede ir vacio.'
						}
					}
				},
				fec_ini : {
					validators : {
						notEmpty : {
							message : "Ingresa una fecha de inicio no puede ir vacío."
						}
					}
				},
				fec_fin : {
					validators : {
						notEmpty : {
							message : "Ingresa una fecha final no pude ir vacío."
						}
					}
				}
			}
		}	).on( 'success.form.bv', function() {
			var form = $( this );
			var dir = form.attr( 'action' );
			var data_form = form.serialize();
			
			exportAjax( dir, data_form, form );
		} ).on( 'error.form.bv', function( e ) {
			alertMessage( 'Valida todos los campos en rojo.', 'alertify-info');
		} );
	}
	
	$( '.send-form' ).on( 'click', function() {
		$( this ).parents( 'div.modal-content' ).find( 'form.form-val-bit' ).data( 'bootstrapValidator' ).revalidateField( 'fec_ini' );
		$( this ).parents( 'div.modal-content' ).find( 'form.form-val-bit' ).bootstrapValidator( 'validate' );
	} );
	
	$( '#btn-exp-user' ).on( 'click', function() {
		$( '#modal-loader' ).modal( 'show' );
		exportAjax( path +"index.php/bitacoras/exporta_usuarios/", "", $( this ) );
	} );
	
	if( $( '.input-date-calendar' ).length ) {
		$( '.input-date-calendar' ).datepicker( {
			showOn: "button",
			buttonImage: path +"media/images/Actions-view-calendar-day-icon.png",
			buttonImageOnly: true,
			buttonText: "Selecciona Fecha",
			dateFormat: 'yy-mm-dd',
			closeText: closeText,
			prevText: prevText,
			nextText: nextText,
			currentText: currentText,
			monthNames: monthNames,
			monthNamesShort: monthNamesShort,
			dayNames: dayNames,
			dayNamesShort: dayNamesShort,
			dayNamesMin: dayNamesMin,
			weekHeader: weekHeader,
		} );
	}
} );
