$( document ).ready( function() {
	//document.writeln(screen.width + " x " + screen.height);
	//var documentHeight = $( document ).height();
	resizeBody();
	// setTimeout( function() {
	// 	var screenHeight = $( window ).height();
	// 	var bodyHeight = ( $( '#div-body' ).length ? $( '#div-body' ).innerHeight() : $( '#body-layout').innerHeight() );
	// 	var realBodyHeight = ( $( '#div-body' ).length ? $( '#div-body' ).height() : $( '#body-layout').height() );
	// 	var footerHeight = $( '#div-footer' ).innerHeight();
	// 	if( bodyHeight < screenHeight ) {
	// 		var bodyHe = screenHeight - ( footerHeight + bodyHeight + 16 );
	// 		var heightBody = bodyHe + realBodyHeight;
	// 		( ($( '#div-body' ).length) ? $( '#div-body' ).height( heightBody ) : $( '#body-layout' ).height( ( heightBody + 70 ) ) );
	// 	}
	// 	// resizeBody();
	// }, 2000);
	
	if( $( '#reloj-layout' ).length ) {
		//horaServer();//Hora del servidor
		//jQuery.displayDateTime(); //hora del cliente
		if( (navigator.userAgent.indexOf("MSIE ") > -1) === true || !!navigator.userAgent.match(/Trident.*rv\:11\./) ) {
			//jQuery.displayDateTime(); //hora del cliente
			horaServer();//Hora del servidor
		} else {
			relojServer();//Hora del servidor
		}
	}
	
	if( $( '#session-layout' ).length ) {
		getJson( path, { 'controlador' : 'DataSession', 'accion' : 'getSessionJson' }, function( info ) {
			if( info.session === true ) {
				var dropdown = '<a href="#" class="dropdown-toggle" id="user-name" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'+
					'<i class="fa fa-user-circle-o fa-2" aria-hidden="true"></i> '+ info.no +" "+ info.ap +
					'<span class="caret"></span>'+
					'</a>'+
					'<ul class="dropdown-menu" aria-labelledby="usuario-menu"> '+
					// '<li><a href="javascript:void(null)"><i class="fa fa-columns" aria-hidden="true"></i> dd: '+ info.dd +'</a></li>'+
					'<li><a href="javascript:void(null)"><i class="fa fa-address-card" aria-hidden="true"></i> Perfil: '+ info.role +'</a></li>'+
					'<li role="separator" class="divider"></li> '+
					'<li><a href="javascript:void(null)" id="close-session"><i class="fa fa-power-off" aria-hidden="true"></i> Cerrar sesión</a></li>'+
					'</ul>';
				$( '#session-layout' ).html( dropdown );
			}
		});
	}
	
	if( $( '#user-name-session' ).length ) {
		$( 'a#dropdown-menu-user-name > span.ui-icon' ).css( { 'background-image' : 'url('+ path +
			'media/librerias/jquery-ui-themes-1.12.1/themes/base/images/ui-icons_ffffff_256x240.png)' } );
		$('#dropdown-menu-user-name').menu({
			content: $('#menu-dropdown-user').html(),
			showSpeed: 400
		});
		$( '#dropdown-menu-user-name' ).on('click', function(e) {
			if( $('#menu-dropdown-user:visible').length == 0 ) {
				$( '#menu-dropdown-user' ).css( 'display', 'block');
				$( '#dropdown-menu-user-name' ).addClass('open');
			} else {
				$( '#menu-dropdown-user' ).css( 'display', 'none');
				$( '#dropdown-menu-user-name' ).removeClass('open');
			}
			e.stopPropagation();
		});

		$( 'html' ).on( 'click', function() {
			if( !$('#menu-dropdown-user:visible').length == 0 ) {
				$( '#menu-dropdown-user' ).css( 'display', 'none');
				$( '#dropdown-menu-user-name' ).removeClass('open');
			}
		});
	}
	$( '#div-loader-modal' ).css( 'background', 'url('+ path +'media/images/ui-bg_diagonals-thick_20_666666_40x40_n.png)' );
});

$( document ).on( "click", "#close-session", function() {
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
});

function relojServer()
{
	var date = '';
	var format = '';
	var hours = '';
	var minutes = '';
	var seconds = '';
	var count = '';
	getJson( path +"/index.php/home/relojServer/", "", function( reloj ) {
		date = reloj.date;
		format = reloj.format;
		hours = reloj.hour;
		minutes = reloj.minute;
		seconds = reloj.seconds;
		count = 0;
		generaReloj( count, date, format, hours, minutes, seconds );
	});
}

function generaReloj( count, date, format, hours, minutes, seconds )
{
	minutes = parseInt( minutes );
	seconds = parseInt( seconds );
	hours = parseInt( hours );
	seconds++;
	count++;
	if( seconds == 60 ) {
		seconds = 0;
		minutes++;
		if( minutes == 60 ) {
			minutes = 0;
			hours++;
		}
	}
	var minute = ( minutes < 10 ) ? "0"+ minutes : minutes;
	var second = ( seconds < 10 ) ? "0"+ seconds : seconds;
	var hour = ( hours < 10 ) ? "0"+ hours : hours;
	var fec = date +" "+ hour +":"+ minute +":"+ second +" "+ format;
	$( '#reloj-layout' ).text( fec );
	
	if( count > 900 ) {
		ajaxDinamic( 'GET', path +"/index.php/home/relojServer/", 'json', '', '1000', '', function( data ) {
			if( data.err == true ) {
				timeReloj = setTimeout( function() {
					generaReloj( 0, date, format, hours, minutes, seconds );
				}, 1000);
			}
			generaReloj( 0, data.date, data.format, data.hour, data.minute,  data.seconds );
		});
	} else {
		timeReloj = setTimeout( function() {
			generaReloj( count, date, format, hours, minutes, seconds );
		}, 1000);
	}
}

function horaServer()
{
	//setInterval(function(){
		//$( '#relog-layout' ).load( path +'?controlador=home&accion=reloj' );
	setPost( path, { 'controlador' : 'home',  'accion' : 'reloj' }, function( reloj ) {
		//var fecha_js = new Date( reloj );
		var segundos = parseInt(reloj.substr(17,2));
		var hora = parseInt(reloj.substr(11, 2));
		var minutos = parseInt(reloj.substr(14, 2));

		muestraRelojServer( segundos, minutos, hora );
	} );
	//}, 1000);
}

function muestraRelojServer( segundos, minutos, hora )
{
	var now = new Date( );
	var months = new Array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
	var year = now.getFullYear();
	var month = now.getMonth();
	var day = now.getDate();
	day = ( day < 10 ) ? "0"+ day : day;
			
	var fec = day +'-'+ months[month] +'-'+ year;

	segundos++;
	if (segundos == 60) {
		segundos = 0;
		minutos++;
		if (minutos == 60) {
			minutos = 0;
			hora++;
			if (hora == 24) {
				hora = 0;
			}
		}
	}
	
	var minute = ( minutos < 10 ) ? "0"+ minutos : minutos;
	var second = (segundos < 10 ) ? "0"+ segundos : segundos;
	
	if( hora > 11 ) 
	{
		var format = "PM";
	}
	else 
	{
		var format = "AM";
	}
	
	var horas = new Array('12','01','02','03','04','05','06','07','08','09','10','11','12','01','02','03','04','05','06','07','08','09','10','11');

	var time = horas[parseInt(hora)] +':'+ minute +':'+ second;
	var fecha = fec +' '+ time +' '+ format;
	$( '#reloj-layout' ).html( fecha );
	setTimeout( function() {
		muestraRelojServer( segundos, minutos, hora );
	},997 );
}

jQuery.displayDateTime = function( element ) {
	var now = new Date();
	var months = new Array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
	var date = now.getDate();
	var year = now.getFullYear();
	var month = now.getMonth();
	var day = now.getDate();
	var hour = now.getHours();
	var minute = now.getMinutes();
	var second = now.getSeconds();
	var horas = new Array('12','01','02','03','04','05','06','07','08','09','10','11','12','01','02','03','04','05','06','07','08','09','10','11');

	//hour = ( hour < 10 ) ? "0"+ hour : hour;
	minute = ( minute < 10 ) ? "0"+ minute : minute;
	second = (second < 10 ) ? "0"+ second : second;
	day = ( day < 10 ) ? "0"+ day : day;
	
	if( hour > 11 ) 
	{
		var format = "PM";
	}
	else 
	{
		var format = "AM";
	}

	var datetime = day +'-'+ months[month] +'-'+ year +' '+ horas[parseInt(hour)] +':'+ minute +':'+ second +' '+ format;

	element = '#relog-layout';
	$( element ).html( datetime );
	setTimeout( 'jQuery.displayDateTime("'+ element +'");','1000' );
}
