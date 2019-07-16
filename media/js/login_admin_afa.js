$( function() {
	$( '#btn-login' ).on( 'click', function(e) {
		e.preventDefault();
		$( '#err-msg' ).html( '' );
		var user = $( '#usuario' ).val();
		var pass = $( '#passwd' ).val();
		
		if( (navigator.userAgent.indexOf("MSIE ") > -1) === false ) {
			if( user.trim() == "" ) {
				var msg = '<div class="alert alert-danger" role="alert">Ingresa un usuario.</div>';
				$( '#err-msg' ).html( msg );
				return false;
			} else if( pass.trim() == "" ) {
				var msg = '<div class="alert alert-danger" role="alert">Ingresa una contraseña.</div>';
				$( '#err-msg' ).html( msg );
				return false;
			}
		}
		
		$( '#modal-loader' ).modal( 'show' );
		$( '#btn-login' ).attr( 'disabled', 'disabled' );
		var serial = $("#form-login").serialize();

		setPost( path +"/index.php/login/loginStart/", serial, function( response ) {
			console.log(response);
			$( '#btn-login' ).removeAttr( 'disabled' );
			$( '#modal-loader' ).modal( 'hide' );
			if( typeof response.valid === "undefined" ) {
				$( '#err-msg' ).html( '<p class="bg-danger text-danger">Ocurrio un error! '+ response +'.</p>' );
				return false;
			}
			if( response.valid === true ) {
				var msg = '<div class="alert alert-success" role="alert">'+ response.message +'</div>';
				$( '#err-msg' ).html( msg );
				setTimeout( function() {
					window.location.href = response.href;
				}, 500);
			}
			if( response.valid === false ) {
				var msg = '<div class="alert alert-danger" role="alert">'+ response.message +'</div>';
				$( '#err-msg' ).html( msg );
			}
		} );


	} );
} );
