$(function(){

    //VALIDACION FORMULARIO LIBERAR
    $('#form-usuarios')
    .bootstrapValidator({
        message: 'Valor no es válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        excluded: [':disabled'],
        fields: {
            tipo_user: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione el tipo de usuario'
                    }
                }
            },
            no: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese un nombre'
                    }
                }
            },
            ap: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese Apellido Paterno'
                    }
                    ,regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'Por favor, solo utilice números y letras'
                    }
                }
            },
            cl: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione siglas'
                    }
                }
            },
            ni: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el correo del usuario'
                    }
                    ,emailAddress: {
                        message: 'Se detecto un correo no válido'
                    }
                }
            },
            pu: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione puesto'
                    }
                }
            },
            lr: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione rol del sistema'
                    }
                }
            }

        }
    });

})