
$(function(){

    var e = {
        url: path + "index.php/tablero/",
        data: null,
        filtroGerencia: "",
        idsPeriodos: null,
        predicion: null,
        urlIntervalos: path + "index.php/reportes/"
    }

    var setSolicitudesNuevas = function(){
        var gerencia = e.filtroGerencia == "" ? "" : ['al', '=', e.filtroGerencia, 'string'];
        var filtros = {
            gerencia: gerencia
        };
        var dataGet = { filtros: filtros };

        getJson(e.url + "solicitudesNuevas", dataGet, function(data){
            $("#sol_nuevas").text( data['data'][0]['nuevas'] );
        });
    }

    // setSolicitudesNuevas();

    var generaGraficas = function(filtros = ""){

        var dataGet = { filtros: filtros }; 

        //para el listdado cuando den clic
        var gerencia = e.filtroGerencia == "" ? "" : ['al', '=', e.filtroGerencia, 'string'];
        var fl = {
            proceso: ['a.et', '=', '619056264933549', 'string'],
            gerencia: gerencia
        };

        getJson(e.url + "dataTablero", dataGet, function(data){
            e.data = data;
            liberadasPorMes('Evaluaciones Liberadas', 'evaluaciones-liberadas', data.liberadas, function (event) { generaTablaBasic(this.name, e.filtroGerencia); });
            generaTablaBasic('2019', e.filtroGerencia);
            liberadasPorMes('Acumulado de Evaluaciones Liberadas','evaluaciones-acumulado', data.acumulado);

            indicadorTiempos('indicador-tiempos', data.periodos[0], function (event) {
                var t = event.point.name;
                fl.listado = ['a.dl', '=', data.periodos[0].maximo, ''];
                fl.rango1 = ['DATEDIFF(NOW(),fs)', '>', event.point.desde[0], ''];
                fl.rango2 = ['DATEDIFF(NOW(),fs)', '<=', event.point.desde[1], ''];
                var dataGet = { filtros: fl };
                generaListadoPerido(dataGet, t, 15);
            });

            indicadorTiempos('indicador-tiempos2', data.periodos[1], function (event) {
                console.log(event);
                var t = event.point.name;
                fl.listado = ['a.dl', '=', data.periodos[1].maximo, ''];
                fl.rango1 = ['DATEDIFF(NOW(),fs)', '>', event.point.desde[0], ''];
                fl.rango2 = ['DATEDIFF(NOW(),fs)', '<=', event.point.desde[1], ''];
                var dataGet = { filtros: fl };
                generaListadoPerido(dataGet, t, 30);
            });

            indicadorTiempos('indicador-tiempos3', data.periodos[2], function (event) {
                var t = event.point.name;
                fl.listado = ['a.dl', '=', data.periodos[2].maximo, ''];
                fl.rango1 = ['DATEDIFF(NOW(),fs)', '>', event.point.desde[0], ''];
                fl.rango2 = ['DATEDIFF(NOW(),fs)', '<=', event.point.desde[1], ''];
                var dataGet = { filtros: fl };
                generaListadoPerido(dataGet, t, 45);
            });

            indicadorTiempos('indicador-tiempos4', data.periodos[3], function (event) {
                var t = event.point.name;
                fl.listado = ['a.dl', '=', data.periodos[3].maximo, ''];
                fl.rango1 = ['DATEDIFF(NOW(),fs)', '>', event.point.desde[0], ''];
                fl.rango2 = ['DATEDIFF(NOW(),fs)', '<=', event.point.desde[1], ''];
                var dataGet = { filtros: fl };
                generaListadoPerido(dataGet, t, 60);
            });

            indicadorTiempos('indicador-tiempos5', data.periodos[4], function (event) {
                var t = event.point.name;
                fl.listado = ['a.dl', '=', data.periodos[4].maximo, ''];
                fl.rango1 = ['DATEDIFF(NOW(),fs)', '>', event.point.desde[0], ''];
                fl.rango2 = ['DATEDIFF(NOW(),fs)', '<=', event.point.desde[1], ''];
                var dataGet = { filtros: fl };
                generaListadoPerido(dataGet, t, 90);
            });

            generaTablaPrediccion(filtros);
            generaTablaPredEspec(filtros);
            setSolicitudesNuevas();
        });

    }

    var generaListadoPerido = function(dataGet, t, diasVence){

        getJson(e.url + "getListado", dataGet, function(data){ 

            $("#modal-head-title").text( t );

            var items = [];
            var count = 1;
            $.each(data.data, function(i, v){
                var tr = $("<tr>");
                tr.append( $("<td>").html(count) );
                tr.append( $("<td>").html(v.el).addClass('bg-success') );
                tr.append( $("<td>").html(v.no) );
                tr.append( $("<td>").html(v.s_lab) );
                tr.append( $("<td>").html(v.subgerente) );
                tr.append( $("<td>").html(v.fs).addClass('bg-info') );
                tr.append( $("<td>").html(v.dias_t).addClass('bg-warning') );
                // var diasVence = 15;
                var diasParaVencer = parseInt(diasVence) - parseInt(v.dias_t);
                if( diasParaVencer > 0  ){
                    var text = diasParaVencer;
                    var bg = '';
                }else{
                    var text = "vencido por " + Math.abs(diasParaVencer);
                    var bg = 'bg-danger1';
                }
                // var text = ( diasParaVencer > 0 ) ? diasParaVencer + " para vencer" : "vencido por " + Math.abs(diasParaVencer);
                tr.append( $("<td>").html(text).addClass(bg) );
                items.push(tr[0].outerHTML);
                count++;
            })

            $("#table-listado-detalle tbody").html( items.join('') );
            $("#modal-listado").modal('show');

        })

    }

    //se maneja a parte, ya que las demas graficas se pueden generar con base en la gerencia seleccionada
    var generaGerencias = function(){
        getJson(e.url + "evaluacionesGerencia", null, function(data){
            indicadorPieChart('evaluaciones-gerencia', data, function (event) {
                e.filtroGerencia = event.point.ix;
                $("#text-filtro").text(event.point.desc);
                $("#div-filtro").show("slow");
                var filtros = {
                    gerencia: ['al', '=', event.point.ix, 'string']
                };
                generaGraficas(filtros);
            });
        })
    }

    var generaTablaBasic = function(anio, gerencia = ""){
            
        getJson(e.url + "dataTablaBasic", {anio: anio, gerencia: gerencia}, function(data){
            var items = [];
            var total_porcentaje = 100;
            var total_evaluaciones = 0;
            var total_lib = 0;
            var total_can = 0;
            var anio = 2017;
            $.each(data.data, function(i, v){
                
                anio = v.anio;
                v.cantidad_lib = ( v.cantidad_lib == null ) ? 0 : parseInt(v.cantidad_lib);
                v.liberadas = ( v.liberadas == null ) ? 0 : parseInt(v.liberadas);
                v.canceladas = ( v.canceladas == null ) ? 0 : parseInt(v.canceladas);

                total_evaluaciones += v.cantidad_lib;
                total_lib += v.liberadas;
                total_can += v.canceladas;

                var tr = $("<tr>").addClass('item-table-basic tr-link').attr({'id': v.anio_mes, 'data-title': "Detalle evaluaciones liberadas periodo: "+v.anio_mes+" "+$("#text-filtro").text() });
                tr.append( $("<td>").html(v.anio_mes) );
                tr.append( $("<td>").html(v.porcentaje + " %") );
                tr.append( $("<td>").html(v.cantidad_lib) );
                tr.append( $("<td class='bg-success'>").html(v.liberadas) );
                tr.append( $("<td class='bg-danger'>").html(v.canceladas) );
                items.push( tr[0].outerHTML );
            })
            var total = "<tr class='bg-info item-table-basic' id='"+anio+"' data-title='Detalle de evaluaciones liberadas "+anio+" "+$("#text-filtro").text()+"'><td>Total</td><td>"+total_porcentaje+" %</td><td>"+total_evaluaciones+"</td><td>"+total_lib+"</td><td>"+total_can+"</td></tr>";
            $("#tbl-evaluaciones-liberadas tbody").html(items.join(''));
            $("#tbl-evaluaciones-liberadas tbody").append(total);
            // $("#tbl-evaluaciones-liberadas").DataTable({destroy: true, searching: false, paging: false, info: false});
        })

    }

    var generaTablaPrediccion = function(filtros = ""){
        var items = [];
        var enProceso = 0;
        var dataGet = { filtros: filtros };

        getJson(e.url + "dataTablaPrediccion", dataGet, function(data){

            e.idsPeriodos = data.ids;
            var texto = { 
                '1_15': [ '1 a 15', '#33FF58'], 
                '16_30': [ '16 a 30', '#00ff00'], 
                '31_60': [ '31 a 60', '#AAff00'], 
                '61_90': [ '61 a 90', '#ffff00'], 
                '91_120': [ '91 a 120', '#ff8800']
            };
            
            enProceso = data['cant']['1_15'][0] + data['cant']['16_30'][0] + data['cant']['31_60'][0] + data['cant']['61_90'][0];

            // $.each(data.cant, function(i, v){

            //     var classLink = ( parseInt(v[0]) > 0 ) ? 'tr-link' : '';

            //     var tr = $("<tr>");
            //     tr.append( $("<td>").css( {'background-color': texto[i][1]} ) );
            //     tr.append( $("<td>").html(texto[i][0]) );
            //     tr.append( $("<td>").addClass('item-table-prediccion '+classLink ).attr({'data-per': i, 'data-title': "Detalle evaluaciones de "+texto[i][0]+" días. "+$("#text-filtro").text() }).html(v[0]) );
            //     tr.append( $("<td>").html(v[1]) );
            //     tr.append( $("<td>").html(v[2]) );
            //     tr.append( $("<td>").html(v[3]) );
            //     tr.append( $("<td>").html(v[4]) );
            //     tr.append( $("<td>").html(v[5]) );
            //     items.push( tr[0].outerHTML );
            // })

            $("#en_proceso").html(enProceso);
            // $("#tbl-prediccion tbody").html(items.join(''));
            // $("#tbl-prediccion").DataTable({destroy: true, searching: false, paging: false, info: false});

        })

    }


    var generaTablaPredEspec = function(){

        var gerencia = e.filtroGerencia == "" ? "" : ['al', '=', e.filtroGerencia, 'string'];
        var filtros = {
            proceso: ['a.et', '=', '619056264933549', 'string'],
            gerencia: gerencia
        };
        var dataGet = { filtros: filtros };
        getJson(e.urlIntervalos + "Intervalos", dataGet, function(data){

            e.predicion = data;
            $("#total-1").text(data.totales[0]);
            $("#total-2").text(data.totales[1]);
            $("#total-3").text(data.totales[2]);
            $("#total-4").text(data.totales[3]);

        })

    }


    var liberadasPorMes = function(titulo, id, data, callback){

        Highcharts.chart(id, {
            chart: {
                height: 350,
            },
            title: {
                text: titulo,
                style: {
                    color: "#0282EB"
                }
            },
            credits: {
                enabled: false
            },
            yAxis: {
                title: {
                    text: 'Evaluaciones Liberadas'
                }
            },
            xAxis: {
                categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
            },
            plotOptions: {
                series: {
                    cursor: 'pointer',
                    events: {
                        click: callback
                    }
                }
            },
            series: data,
            colors: ['#00825A', '#0282EB', '#7D2181']
            // series: [{
            //     name: '2018',
            //     data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
            //     }, {
            //     name: '2019',
            //     data: [216.4, 194.1, 95.6, 54.4, 29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5],
            //     lineWidth: 5
            // }]

        });

    }

    var indicadorTiempos = function(id, data, callback = null){

        Highcharts.chart(id, {
            chart: {
                height: 165,
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: data.titulo,
                align: 'center',
                verticalAlign: 'middle',
                y: 50,
                style: {
                    fontSize: "11px"
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false,
                        distance: -50,
                        style: {
                            fontWeight: 'bold',
                            color: 'white'
                        }
                    },
                    startAngle: -90,
                    endAngle: 90,
                    center: ['50%', '75%'],
                    size: '110%'
                },
                series: {
                    cursor: 'pointer',
                    events: {
                        click: callback
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Porcentaje',
                innerSize: '50%',
                data: data.values
                // data: [
                //     ['Chrome', 58.9],
                //     ['Firefox', 13.29],
                //     ['Internet Explorer', 13],
                //     ['Edge', 3.78],
                //     ['Safari', 3.42],
                //     {
                //         name: 'Other',
                //         y: 7.61,
                //         color: '#000',
                //         dataLabels: {
                //             enabled: false
                //         }
                //     }
                // ]
            }],
            colors: [ '#49ff00', '#fffc00', '#e00' ]
        });

    }


    var indicadorPieChart = function(id, data, callback = null){

        Highcharts.chart(id, {
            chart: {
                height: 420,
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: data.titulo,
                style: {
                    color: "#0282EB"
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                pointFormat: '{point.desc}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                },
                series: {
                    cursor: 'pointer',
                    events: {
                        click: callback
                    }
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: data.values
            }]
        });

    }

    var indicador3dPie = function(id, data, callback = null){

        Highcharts.chart(id, {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: data.titulo,
                style: {
                    color: "#0282EB"
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                pointFormat: '{point.cant} - {series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                },
                series: {
                    cursor: 'pointer',
                    events: {
                        click: callback
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'representa un',
                data: data.values

                // [
                //     ['Firefox', 45.0],
                //     ['IE', 26.8],
                //     {
                //         name: 'Chrome',
                //         y: 12.8
                //         // sliced: true,
                //         // selected: true
                //     },
                //     ['Safari', 8.5],
                //     ['Opera', 6.2],
                //     ['Others', 0.7]
                // ]
            }]
        });

    }


    var indicador3dCilinder = function(id, data, callback = null){

        Highcharts.chart('evaluaciones-tipo-solicitud', {
            chart: {
                type: 'cylinder',
                options3d: {
                    enabled: true,
                    alpha: 15,
                    beta: 15,
                    depth: 50,
                    viewDistance: 25
                }
            },
            title: {
                text: data.titulo,
                style: {
                    color: "#0282EB"
                }
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    depth: 25,
                    colorByPoint: true
                }
            },
            series: [{
                // data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                data: data.values,
                name: 'Cylinders',
                showInLegend: false
            }]
        });

    }

    generaGraficas();
    generaGerencias();

    $("#btn-del-filtro").click(function(){
        e.filtroGerencia = "";
        $("#text-filtro").text("");
        $("#div-filtro").hide("hide");
        generaGraficas();
        generaGerencias();
    })


    $(".td-link").click(function(){

        var id = $(this).attr('id');
        ids = e.predicion.ids[id];

        var title = $(this).attr('data-title');

        var i = [];
        var assing = [];
        $.each(ids, function(ind, v){
            i.push(v[0]);
            assing[''+v[0]] = v[1];
        })

        var dataGet = { ids: i }; 
        getJson(e.urlIntervalos + "IntervalosDetalle", dataGet, function(res){

            $("#modal-head-title").text( title );
 
            var items = [];
            var count = 1;
            $.each(res.data, function(i, v){
                var tr = $("<tr>");
                tr.append( $("<td>").html(count) );
                tr.append( $("<td>").html(v.el).addClass('bg-success') );
                tr.append( $("<td>").html(v.no) );
                tr.append( $("<td>").html(v.s_lab) );
                tr.append( $("<td>").html(v.subgerente) );
                tr.append( $("<td>").html(v.fs).addClass('bg-info') );
                tr.append( $("<td>").html(v.dias_t).addClass('bg-warning') );
                var diasVence = assing[v.id];
                var diasParaVencer = parseInt(diasVence) - parseInt(v.dias_t);
                if( diasParaVencer > 0  ){
                    var text = diasParaVencer;
                    var bg = '';
                }else{
                    var text = "vencido por " + Math.abs(diasParaVencer);
                    var bg = 'bg-danger1';
                }
                // var text = ( diasParaVencer > 0 ) ? diasParaVencer + " para vencer" : "vencido por " + Math.abs(diasParaVencer);
                tr.append( $("<td>").html(text).addClass(bg) );
                items.push(tr[0].outerHTML);
                count++;
            })

            $("#table-listado-detalle tbody").html( items.join('') );
            $("#modal-listado").modal('show');

        })

    });



    $("#tbl-evaluaciones-liberadas").on('click', '.item-table-basic', function(){
        var anio_mes = $(this).attr('id');
        var title = $(this).attr('data-title');
        var gerencia = e.filtroGerencia == "" ? "" : ['al', '=', e.filtroGerencia, 'string'];

        var filtros = {
            anio_mes: ['fl', 'LIKE', anio_mes+'%', 'string'],
            gerencia: gerencia
        };
        var dataGet = { filtros: filtros };

        getJson(e.url + "presentacionGeneral", dataGet, function(data){

            $("#modal-detalle #modal-detalle-title").text(title);
            $("#modal-detalle").modal('show');

            data['proveedor']['titulo'] = "Proveedores";
            indicador3dPie('evaluaciones-provedor', data.proveedor, function(event){
                var name = event.point.name;
                var t = title + " Proveedor: " + name;
                filtros.listado = ['e.no', 'LIKE', name, 'string'];
                listaPresentacionGeneral("getListado", filtros, t);
            });

            data['mercado']['titulo'] = "Mercado";
            indicador3dPie('evaluaciones-mercado', data.mercado, function(event){
                var name = event.point.name;
                var t = title + " Mercado: " + name;
                filtros.listado = ['m.no', 'LIKE', name, 'string'];
                listaPresentacionGeneral("getListado", filtros, t);
            });

            data['solicitud']['titulo'] = "Tipo de solicitud";
            indicador3dPie('evaluaciones-tipo-solicitud', data.solicitud, function(event){
                var name = event.point.name;
                var t = title + " Tipo de solicitud: " + name;
                filtros.listado = ['g.no', 'LIKE', name, 'string'];
                listaPresentacionGeneral("getListado", filtros, t);
            });

            data['cliente']['titulo'] = "Cliente Solicitante";
            indicador3dPie('evaluaciones-cliente', data.cliente, function(event){
                var name = event.point.name;
                var t = title + " Cliente: " + name;
                filtros.listado = ['b.cl', 'LIKE', name, 'string'];
                listaPresentacionGeneral("getListado", filtros, t);
            });

        });

    })


    var listaPresentacionGeneral = function(urlJson, filtros, title){
        var dataGet = { filtros: filtros };
        getJson(e.url + urlJson, dataGet, function(data){ 
            console.log(data);

            $("#modalib-head-title").text( title );
 
            var items = [];
            var count = 1;
            $.each(data.data, function(i, v){
                var tr = $("<tr>");
                tr.append( $("<td>").html(count) );
                tr.append( $("<td>").html(v.el).addClass('bg-success') );
                tr.append( $("<td>").html(v.no) );
                tr.append( $("<td>").html(v.s_lab) );
                tr.append( $("<td>").html(v.fs).addClass('bg-info') );
                tr.append( $("<td>").html(v.fl).addClass('bg-warning') );
                items.push(tr[0].outerHTML);
                count++;
            })

            $("#table-listadolib-detalle tbody").html( items.join('') );
            $("#modal-listadolib").modal('show');

        });
    }


    $("#tbl-prediccion").on('click', '.item-table-prediccion', function(){
        var title = $(this).attr('data-title');
        var rango = $(this).attr('data-per');
        var gerencia = e.filtroGerencia == "" ? "" : ['al', '=', e.filtroGerencia, 'string'];
        var idIn = [];

        var rangos = { 
            '1_15': [1, 15], 
            '16_30': [16, 30], 
            '31_60': [31, 60], 
            '61_90': [61, 90], 
            '91_120': [91, 120]
        };

        var filtros = {
            gerencia: gerencia
        };
        var dataGet = { filtros: filtros, rangos: rangos[rango] };

        getJson(e.url + "periodoUnico", dataGet, function(data){

            if( data.data.length > 0 ){

                $.each(data.data, function(i, v){
                    idIn.push(v.id);
                })

                var filtros = {
                    ids: ['a.id', 'IN', '('+idIn.join(',')+')' ]
                };

                getJson(e.url + "presentacionGeneral", { filtros: filtros }, function(data){
                    $("#modal-detalle #modal-detalle-title").text(title);
                    $("#modal-detalle").modal('show');

                    data['proveedor']['titulo'] = "Proveedores";
                    indicador3dPie('evaluaciones-provedor', data.proveedor);

                    data['mercado']['titulo'] = "Mercado";
                    indicador3dPie('evaluaciones-mercado', data.mercado);

                    data['solicitud']['titulo'] = "Tipo de solicitud";
                    indicador3dPie('evaluaciones-tipo-solicitud', data.solicitud);
                });

            }
 
            
        });

    })


})



