$(function(){

	var e = {
		urlEval: path + "index.php/evaluaciones/"
	}


	var notificacionCorreoTest = function(ele){
		var filtros = {
            ident: ['a.el', '=', 'Ele-802/2019', 'string']
        };
        var dataPost = { filtros: filtros };
    	setPost(e.urlEval + "notificacionCorreo", dataPost, function(response){
			console.log(response);
		});
	}
	notificacionCorreoTest();



})