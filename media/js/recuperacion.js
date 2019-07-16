$(function(){

	var e = {
		url: path + "index.php/recuperacion/"
	}


	$("#btn-actualizar-pwd").click(function(){

		var validator = $('#form-recuperacion').data('bootstrapValidator');
        validator.validate();
        if (!validator.isValid())
        	return false;

        var data = $("#form-recuperacion").serialize();
        setPost(e.url + "actualizarPassword", data, function(response){
			console.log(response);
			if( response === true ){
				mensaje = "Se actualizo la contraseña.";
				clase = "alertify-success";

				alertMessage(mensaje, clase);
				
				var data = { "controlador" : "login", "accion" : "close_session_afa_admin" };
				setPost( path, data, function( info ) {
					alertMessage(info.mensaje, info.clase);
					if( info.valid === true )
					{
						setTimeout( function(){ 
							window.location.href = info.redirecciona;
						}, 2000);
					}
				});

			}else{
				mensaje = response;
				clase = "alertify-danger";
				alertMessage(mensaje, clase);
			}
			

		});

	})

})