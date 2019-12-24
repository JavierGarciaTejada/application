$(function(){

    $("#form-inv-equipos")
    .bootstrapValidator({
        fields: {
            red: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese RED'
                    }
                }
            },
            equipo: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione Equipo'
                    }
                }
            },
            tecnologia: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese Tecnología'
                    }
                }
            },
            proveedor: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese Proveedor'
                    }
                }
            }
        }
    });


})