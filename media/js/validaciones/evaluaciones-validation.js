$(function(){

    $("#evaluacion")
    .bootstrapValidator({
        fields: {
            el: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese Ele'
                    }
                }
            },
            // cl: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Ingrese la referencia solicitante'
            //         }
            //     }
            // },
            al: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione a quien va dirigida la solicitud'
                    }
                }
            },
            no: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el nombre de la evaluación'
                    }
                }
            },
            ac: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione las sigla del cliente'
                    }
                }
            },
            pv: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione proveedor'
                    }
                }
            },
            ts: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione Tipo de solicitud'
                    }
                }
            },
            pr: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione prioridad'
                    }
                }
            },
            // fo: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Ingrese fecha compromiso'
            //         }
            //     }
            // },
            fs: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese fecha de solicitud'
                    }
                }
            }
        }
    });

    //VALIDACION FORMULARIO PARA ACEPTAR
	$('#form-aceptar')
	.bootstrapValidator({
        message: 'Valor no es válido',
        // feedbackIcons: {
        //     valid: 'glyphicon glyphicon-ok',
        //     invalid: 'glyphicon glyphicon-remove',
        //     validating: 'glyphicon glyphicon-refresh'
        // },
        excluded: [':disabled'],
        fields: {
            sg: {
                validators: {
                    notEmpty: {
                        message: 'Debe asignar un Subgerente'
                    }
                }
            },
            ig: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar un Ingeniero'
                    }
                }
            },
            pa: {
                validators: {
                    notEmpty: {
                        message: 'Debe asignar proyecto asociado'
                    }
                }
            },
            me: {
                validators: {
                    notEmpty: {
                        message: 'Debe asignar mercado'
                    }
                }
            },
            meta: {
                validators: {
                    notEmpty: {
                        message: 'Debe asignar meta'
                    }
                }
            },
            nu: {
                validators: {
                    notEmpty: {
                        message: 'Debe asignar Nuevo'
                    }
                }
            },
            te: {
                validators: {
                    notEmpty: {
                        message: 'Debe asignar Tecnología/Equipo'
                    }
                }
            }
        }
    });


    //VALIDACION FORMULARIO RECHAZAR
    $('#form-rechazar')
    .bootstrapValidator({
        message: 'Valor no es válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        excluded: [':disabled'],
        fields: {
            mr: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el motivo de rechazo'
                    }
                }
            }
        }
    });


    //VALIDACION FORMULARIO LIBERAR
    $('#form-liberar')
    .bootstrapValidator({
        message: 'Valor no es válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        excluded: [':disabled'],
        fields: {
            resultado_lib: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione resultado'
                    }
                }
            },
            fecha_liberacion: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione fecha de liberación'
                    }
                }
            },
            ref_liberacion: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese referencia de liberación'
                    }
                }
            }
        }
    });


    //VALIDACION FORMULARIO LIBERAR
    $('#form-cancelar')
    .bootstrapValidator({
        message: 'Valor no es válido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        excluded: [':disabled'],
        fields: {
            fecha_cancelacion: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione fecha'
                    }
                }
            },
            mc: {
                validators: {
                    notEmpty: {
                        message: 'Registre motivo'
                    }
                }
            },
            ref_cancelacion: {
                validators: {
                    notEmpty: {
                        message: 'Registre referencia'
                    }
                }
            }
        }
    });

})