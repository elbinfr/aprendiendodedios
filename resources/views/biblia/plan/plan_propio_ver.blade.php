@extends('master')

@section('topic')
	@include('topic')
@endsection

@section('maincontent')
	<div class="container">
		<div class="row">
			<!-- Opciones -->
			<div class="col-sm-3">
				@include('biblia.plan.partials.opciones')

				<!-- GRAFICO -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Resumen
					</div>
					<div class="fondo-rojo panel-body" id="grafico">											
						
					</div>					
				</div>
			</div>
			<div class="col-sm-9">				
				
				@include('partials.rpta_operacion')
				
				<h3 class="headline first-child" id="typography"><span class="letra-roja">Mis planes de lectura</span></h3>

				<div class="row">
					<!-- FORMULARIO -->
					@include('biblia.plan.partials.fields_ver')
				</div>
				
				<div class="row">
					<div class="page-header">
						<div class="pull-right form-inline">
							<div class="btn-group">
								<button class="btn btn-primary btn-sm" data-calendar-nav="prev"><< Anterior</button>
								<button class="btn btn-sm" data-calendar-nav="today">Hoy</button>
								<button class="btn btn-primary btn-sm" data-calendar-nav="next">Siguiente >></button>
							</div>
						</div>
						<!-- Nombre del Mes -->
						<h4 class="letra-roja"></h4>
					</div>

				</div>
				<div class="row">					
					<div id="calendar"></div>										
				</div>

				<!--ventana modal para el calendario-->
				<div class="modal fade" id="events-modal">
				    <div class="modal-dialog">
					    <div class="modal-content">
					        <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						        <h4 class="modal-title">Modal title</h4>
					        </div>
						    <div class="modal-body" style="height: 400px">
						        <p>One fine body&hellip;</p>
						    </div>
					        <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						        <button type="button" class="btn btn-primary">Save changes</button>
					        </div>
					    </div><!-- /.modal-content -->
				    </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->	

			</div>
		</div>
	</div>
@endsection

@section('script')
<!-- script para bootstrap-calendar -->
{!! Html::script('assets/js/bootstrap-calendar/underscore-min.js') !!}
{!! Html::script('assets/js/bootstrap-calendar/jstz.js') !!}
{!! Html::script('assets/js/bootstrap-calendar/es-ES.js') !!}
{!! Html::script('assets/js/bootstrap-calendar/calendar.js') !!}

<!-- script para grafico -->
{!! Html::script('assets/plugins/highcharts/highcharts.js') !!}
<script>
	var fecha_calendar = $('#fecha_calendar').val();
	(function($){
		//creamos la fecha actual
		var date = new Date(parseInt(fecha_calendar));
		var yyyy = date.getFullYear().toString();
		var mm = (date.getMonth()+1).toString().length == 1 ? "0"+(date.getMonth()+1).toString() : (date.getMonth()+1).toString();
		var dd  = (date.getDate()).toString().length == 1 ? "0"+(date.getDate()).toString() : (date.getDate()).toString();

		//establecemos los valores del calendario
		var options = {
			events_source: '{{ url('biblia/mis-planes-de-lectura/detalle' ) }}',
			view: 'week',
			language: 'es-ES',
			tmpl_path: '{{ asset('assets/bootstrap-calendar/tmpls') }}/',
			tmpl_cache: false,
			day: yyyy+"-"+mm+"-"+dd,
			time_start: '05:00',
			time_end: '23:00',
			time_split: '30',
			width: '100%',
			views:              {
				year:  {
					slide_events: 1,
					enable:       1
				},
				month: {
					slide_events: 1,
					enable:       1
				},
				week:  {
					enable: 1
				},
				day:   {
					enable: 0
				}
			},
			//MIS VARIABLES PROPIAS
			swt_plan: true,
			plan_slug: $('#plan_slug').val(),
			onAfterEventsLoad: function(events) 
			{
				if(!events) 
				{
					return;
				}
				var list = $('#eventlist');
				list.html('');

				$.each(events, function(key, val) 
				{
					$(document.createElement('li'))
						.html('<a href="' + val.url + '">' + val.title + '</a>')
						.appendTo(list);
				});
			},
			onAfterViewLoad: function(view) 
			{
				$('.page-header h4').text(this.getTitle());
				$('.btn-group button').removeClass('active');
				$('button[data-calendar-view="' + view + '"]').addClass('active');
			},
			classes: {
				months: {
					general: 'label'
				}
			}
		};

		var calendar = $('#calendar').calendar(options);

		$('.btn-group button[data-calendar-nav]').each(function() 
		{
			var $this = $(this);
			$this.click(function() 
			{
				calendar.navigate($this.data('calendar-nav'));
			});
		});

		$('.btn-group button[data-calendar-view]').each(function() 
		{
			var $this = $(this);
			$this.click(function() 
			{
				calendar.view($this.data('calendar-view'));
			});
		});

		$('#first_day').change(function()
		{
			var value = $(this).val();
			value = value.length ? parseInt(value) : null;
			calendar.setOptions({first_day: value});
			calendar.view();
		});

		$('#events-in-modal').change(function()
		{
			var val = $(this).is(':checked') ? $(this).val() : null;
			calendar.setOptions(
				{
					modal: val,
					modal_type:'iframe'
				}
			);
		});


		//+++++++++ Grafico ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

		$('#grafico').highcharts({
	        chart: {
	            type: 'column',
	            height: 260
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: [
	                'Estado'	                
	            ],
	            crosshair: true
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Lecturas'
	            }
	        },
	        tooltip: {
	            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0
	            }
	        },
	        series: {!! $datos_grafico !!}
	    });
	}(jQuery));
</script>

@endsection