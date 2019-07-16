$(function(){

    //VALIDACION FORMULARIO LIBERAR
    $('#form-recuperacion')
    .bootstrapValidator({
        message: 'Valor no es válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        excluded: [':disabled'],
        fields: {
            actual: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese su contraseña actual'
                    }
                    ,regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'Por favor, solo utilice números y letras'
                    }
                }
            },
            nueva: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese una nueva contraseña'
                    }
                    ,regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'Por favor, solo utilice números y letras'
                    }
                }
            },
            confirmacion: {
                validators: {
                    notEmpty: {
                        message: 'Confime su nueva contraseña'
                    }
                    ,regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'Por favor, solo utilice números y letras'
                    }
                }
            }
        }
    });

})