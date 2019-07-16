$(function() {
	var characters = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890|!#$%&/()=?¡¿*+{[}];.-_";
	var char_length = characters.length;
	lenguageTable.searchPlaceholder = "Separa con | para varios criterios de búsqueda";
	
	var table_users = $( '#table-users-afa' ).DataTable( {
		"processing" : true,
		"serverSide" : true,
		"language" : lenguageTable,
		"stateSave" : true,
		"scrollX" : true,
		"scrollY" : '75vh',
		"scrollCollapse" : true,
		"search": {
			"regex": true
		},
		"order" : [ 11, 'desc' ],
		"ajax" : {
			"url" : path + "index.php/usuariosafa/get_usuarios_afa/",
			"type" : "POST"
		},
		"columns" : [
			{
				"targets" : 0,
				"class" : "details-control",
				"orderable" : false,
				"data" : null,
				"defaultContent" : "",
				"searchable" : false,
				"createdCell" : function( td, data ) {
					$( td ).parent( 'tr' ).attr( 'id', data.id );
					$( td ).parent( 'tr' ).attr( 'login', data.login );
					$( td ).html(
						'<button class="btn btn-sm btn-primary edit-usr" title="Editar Usuario"><i class="fa fa-pencil" aria-hidden="true"></i></button>'+
						'<button class="btn btn-sm btn-danger delete-usr" title="Eliminar Usuario"><i class="fa fa-trash-o" aria-hidden="true"></i></button>'+
						'<button class="btn btn-sm btn-default change-passwd" title="Cambiar Contraseña"><i class="fa fa-key" aria-hidden="true"></i></button>'
					)
				}
			},
			{ "targets" : 1, "data" : "dd", "name" : "dd" },
			{ "targets" : 3, "data" : "login", "name" : "login" },
			{ "targets" : 4, "data" : "usuario", "name" : "usuario" },
			{ "targets" : 5, "data" : "area", "name" : "area" },
			{ "targets" : 6, "data" : "ip", "name" : "ip" },
			{ "targets" : 7, "data" : "expediente", "name" : "expediente" },
			{ "targets" : 8, "data" : "empresa", "name" : "empresa" },
			{ "targets" : 9, "data" : "telefono", "name" : "telefono" },
			{ "targets" : 10, "data" : "valida_ip", "name" : "valida_ip" },
			{ "targets" : 11, "data" : "reserva", "name" : "reserva" },
			{ "targets" : 12, "data" : "fecha_alta", "name" : "fecha_alta" },
			{ "targets" : 13, "data" : "fecha_pwd", "name" : "fecha_pwd" },
			{ "targets" : 14, "data" : "estilo", "name" : "estilo" },
			{ "targets" : 15, "data" : "reportes", "name" : "reportes" },
			{ "targets" : 16, "data" : "detalles", "name" : "detalles" },
			{ "targets" : 17, "data" : "perfil", "name" : "perfil" },
			{ "targets" : 18, "data" : "status", "name" : "status" },
			{ "targets" : 19, "data" : "fec_ult_ses", "name" : "fec_ult_ses" }
		]
	} );
	
	$( '#btn-add-new-usr' ).on( 'click', function() {
		$( '#modal-label' ).text( "Registrar Usuario Nuevo" );
		$( '#action' ).val( 'insert' );
		$( '#id' ).val( 0 );
		$( '#rows-passwd' ).css( 'display', 'block' );
		$( '#modal-form-usr' ).one( 'shown.bs.modal', function() {
			$( '#form-user-afa' ).data( 'bootstrapValidator' ).resetForm( true );
			$( '#form-user-afa' )[0].reset();
		} ).modal( 'show' );
	} );
	
	$( '#form-user-afa' ).bootstrapValidator( {
		message : 'El valor no es valido.',
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			dd : {
				validators : {
					notEmpty : {
						message : 'La dd no puede ir vacia.'
					}
				}
			},
			login : {
				validators : {
					notEmpty : {
						message : 'El login no puede ir vacío.'
					},
					remote : {
						type : 'POST',
						data : function( validator ) {
							return {
								id : $( '#id' ).val(),
								login : $( '#login' ).val()
							};
						},
						url : path +"index.php/usuariosafa/exists_login/",
						message : 'El login de usuario ya existe intenta con otro.'
					},
					different: {
						field: 'contrasena',
						message: 'El login de usuario no puede ser igual a la contraseña.'
					}
				}
			},
			area : {
				validators : {
					notEmpty : {
						message : 'El area no puede ir vacío.'
					}
				}
			},
			ip : {
				validators : {
					notEmpty : {
						message : 'La ip no puede ir vacío.'
					}
				}
			},
			usuario : {
				validators : {
					notEmpty : {
						message : 'El nombre de usuario no puede ir vacío.'
					}
				}
			},
			telefono : {
				validators : {
					notEmpty : {
						message : 'El teléfono no puede ir vacío.'
					}
				}
			},
			perfil : {
				validators : {
					notEmpty : {
						message : 'El perfil no puede ir vacío.'
					}
				}
			},
			status : {
				validators : {
					notEmpty : {
						message : 'El status no puede ir vacío.'
					}
				}
			},
			contrasena : {
				validators : {
					notEmpty : {
						message : 'La contraseña no puede ir vacia.'
					},
					identical : {
						field : 'passwd_confirm',
						message : 'La contraseña y confirma contraseña no son las mismas.'
					},
					stringLength : {
						min : 6,
						max : 30,
						message : 'La contraseña debe tener una logitud minima de 8 y maxima de 30 caracteres.'
					}
				}
			},
			passwd_confirm : {
				validators : {
					notEmpty : {
						message : 'Confirma la contraseña no puede ir vacia.'
					},
					identical : {
						field : 'contrasena',
						message : 'Confirma contraseña y contraseña no son las mismas.'
					},
					stringLength : {
						min : 6,
						max : 30,
						message : 'La contraseña debe tener una logitud minima de 8 y maxima de 30 caracteres.'
					}
				}
			}
		}
	} ).on( 'success.form.bv', function() {
		$( '#modal-loader' ).modal( 'show' );
		var form_data = $( '#form-user-afa' ).serialize();
		var action = $( '#action' ).val();
		var dir = "";
		if( action == "insert" ) {
			dir = path +"index.php/usuariosafa/inserta_usuario_afa/";
		} else if( action == "update" ) {
			dir = path +"index.php/usuariosafa/actualiza_usuario_afa/";
		}
		setPost( dir, form_data, function( response ) {
			$( '#modal-loader' ).modal( 'hide' );
			if( typeof response.valid === 'undefined' ) {
				alertMessage( response, 'alertify-danger' );
				return false;
			}
			alertMessage( response.message, response.clase );
			if( response.valid == true ) {
				$( '#form-user-afa' ).data( 'bootstrapValidator' ).resetForm( true );
				$( '#form-user-afa' )[0].reset();
				table_users.ajax.reload();
				$( '#modal-form-usr' ).modal( 'hide' );
			}
		} );
	} ).on( 'error.form.bv', function( e ) {
		alertMessage( 'Valida todos los campos en rojo.', 'alertify-info');
	} );
	
	$( '#form-change-passwd' ).bootstrapValidator( {
		message : 'El valor no es valido.',
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			"contrasena" : {
				validators : {
					notEmpty : {
						message : 'La contraseña no puede ir vacia.'
					},
					identical : {
						field : 'passwd_confirm',
						message : 'La contraseña y confirma contraseña no son las mismas.'
					},
					stringLength : {
						min : 6,
						max : 30,
						message : 'La contraseña debe tener una logitud minima de 8 y maxima de 30 caracteres.'
					}
				}
			},
			"passwd_confirm" : {
				validators : {
					notEmpty : {
						message : 'Confirma la contraseña no puede ir vacia.'
					},
					identical : {
						field : 'contrasena',
						message : 'Confirma contraseña y contraseña no son las mismas.'
					},
					stringLength : {
						min : 6,
						max : 30,
						message : 'La contraseña debe tener una logitud minima de 8 y maxima de 30 caracteres.'
					}
				}
			}
		}
	} ).on( 'success.form.bv', function() {
		$( '#modal-loader' ).modal( 'show' );
		var form_data = $( '#form-change-passwd' ).serialize();
		setPost( path +"index.php/usuariosafa/actualiza_passwd/", form_data, function( response ) {
			$( '#modal-loader' ).modal( 'hide' );
			if( typeof response.valid === 'undefined' ) {
				alertMessage( response, 'alertify-danger' );
				return false;
			}
			alertMessage( response.message, response.clase );
			if( response.valid == true ) {
				$( '#form-change-passwd' ).data( 'bootstrapValidator' ).resetForm( true );
				$( '#form-change-passwd' )[0].reset();
				$( '#modal-form-change-pass' ).modal( 'hide' );
				table_users.ajax.reload();
			}
		} );
	} ).on( 'error.form.bv', function( e ) {
		alertMessage( 'Valida todos los campos en rojo.', 'alertify-info');
	} );
	
	$( '#btn-send-form-usr-afa' ).on( 'click', function() {
		$( '#form-user-afa' ).bootstrapValidator( 'validate' );
	} );
	
	$( '#table-users-afa tbody' ).on( 'click', '.delete-usr', function() {
		var id = $( this ).parents( 'tr' ).attr( 'id' );
		var login = $( this ).parents( 'tr' ).attr( 'login' );
		$( '#confirm-delete-user' ).attr( 'id_usr', id );
		$( '#confirm-delete-user' ).attr( 'login', login );
		$( '#name-usr' ).text( login );
		$( '#modal-confirm-delete-user' ).modal( 'show' );
	} );
	
	$( '#table-users-afa tbody' ).on( 'click', '.edit-usr', function() {
		var id_usr = $( this ).parents( 'tr' ).attr( 'id' );
		getJson( path +"index.php/usuariosafa/getDataUsuarioID/"+ id_usr +"/", {}, function( rows ) {
			$( '#modal-label' ).text( "Actualiza Usuario "+ rows.login );
			$( '#action' ).val( 'update' );
			$( '#rows-passwd' ).css( 'display', 'none' );
			$( '#modal-form-usr' ).one( 'shown.bs.modal', function() {
				$( '#form-user-afa' ).data( 'bootstrapValidator' ).resetForm( true );
				$( '#form-user-afa' )[0].reset();
				$.each( rows, function( i, value ) {
					$( '#'+ i ).val( value );
				} );
			} ).modal( 'show' );
		} );
	} );
	
	$( '#confirm-delete-user' ).on( 'click', function() {
		$( '#modal-confirm-delete-user' ).one( 'hidden.bs.modal', function() {
			$( '#modal-loader' ).modal( 'show' );
		} ).modal( 'hide' );
		var id_usr = $( this ).attr( 'id_usr' );
		var login = $( this ).attr( 'login' );
		var data = { "id" : id_usr, "login" : login };
		setPost( path +"index.php/usuariosafa/elimina_usuario_afa/", data, function( response ) {
			$( '#modal-loader' ).modal( 'hide' );
			if( typeof response.valid === "undefined" ) {
				alertMessage( response, 'alertify-danger' );
				return false;
			}
			alertMessage( response.message, response.clase );
			if( response.valid == true ) {
				table_users.ajax.reload();
			}
		} );
	} );
	
	$( '.option-multi-dd' ).on( 'click', function() {
		var value_list = $( this ).attr( 'value' ) +"|";
		var data_input = $( '#dd' ).val();
		if( data_input.indexOf( value_list ) < 0 ) {
			data_input += value_list
		}
		if( data_input.indexOf( "T" ) >= 0 || value_list == "T|" ) {
			data_input = "T";
			alertMessage( 'Se tiene seleccionada todas.', 'alertify-info');
		}
		$( '#dd' ).val( data_input );
		$( '#form-user-afa' ).data( 'bootstrapValidator' ).revalidateField( 'dd' );
	} );
	
	$( '.view-passwd' ).on( 'click', function() {
		var type_passwd = $( '.input-passwd' ).attr( 'type' );
		if( type_passwd == 'password' ) {
			$( '.input-passwd' ).attr( 'type', 'text' );
			$( '.view-passwd > i' ).removeClass( 'fa fa-eye' );
			$( '.view-passwd > i' ).addClass( 'fa fa-eye-slash' );
		} else {
			$( '.input-passwd' ).attr( 'type', 'password' );
			$( '.view-passwd > i' ).removeClass( 'fa fa-eye-slash' );
			$( '.view-passwd > i' ).addClass( 'fa fa-eye' );
		}
	} );
	
	$( '.create-passwd' ).on( 'click', function() {
		var passwd = "";
		var subs = 0;
		for( i = 0; i < 12; i++ ) {
			subs = Math.floor( Math.random() * char_length );
			passwd += characters.substring( subs, ( subs + 1 ) );
		}
		$( '.input-passwd' ).val( passwd );
		$( '#form-change-passwd, #form-user-afa' ).data( 'bootstrapValidator' ).revalidateField( 'contrasena' );
		$( '#form-change-passwd, #form-user-afa' ).data( 'bootstrapValidator' ).revalidateField( 'passwd_confirm' );
	} );
	
	$( '#table-users-afa tbody' ).on( 'click', '.change-passwd', function() {
		var login = $( this ).parents( 'tr' ).attr( 'login' );
		$( '#form-change-passwd' ).find( 'input#id_usr_afa_pass' ).val( $( this ).parents( 'tr' ).attr( 'id' ) );
		$( '#form-change-passwd' ).find( 'input#login_pwd' ).val(  );
		$( '#login_pwd_change' ).text( login );
		$( '#modal-form-change-pass' ).one( 'shown.bs.modal', function() {
			$( '#form-change-passwd' ).data( 'bootstrapValidator' ).resetForm( true );
			$( '#form-change-passwd' )[0].reset();
		} ).modal( 'show' );
	} );
	
	$( '#btn-send-form-change-passwd' ).on( 'click', function() {
		$( '#form-change-passwd' ).data( 'bootstrapValidator' ).revalidateField( 'contrasena' );
		$( '#form-change-passwd' ).data( 'bootstrapValidator' ).revalidateField( 'passwd_confirm' );
		$( '#form-change-passwd' ).bootstrapValidator( 'validate' );
	} );
} );
