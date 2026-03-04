 @extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

@component('admin.components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Dashboard @endslot
@endcomponent



<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg mb-3">
						<label>Nombre del torneo</label>
						<input type="" id="name_t" class="form-control" name="">
					</div>
					<div class="col-lg mb-3">
						<label>Juego</label>
						<select id="game_t" class="form-control form-select">
							<option value="0">Crazy Shoot</option>
							<option value="1">Mines</option>
							<option value="2">X100</option>
							<option value="3">X30</option>
							<option value="4">Dice</option>
							<option value="5">Crash</option>
							<option value="6">Coin Flip</option>
						</select>
					</div>
					<div class="col-lg mb-3">
						<label>Inicio</label>
						<input type="datetime-local" id="start_t" class="form-control" name="">
					</div>
					<div class="col-lg mb-3">
						<label>Fin</label>
						<input type="datetime-local" id="end_t" class="form-control" name="">
					</div>
					<div class="col-lg mb-3">
						<label>Puestos</label>
						<input type="" id="places_t" onkeyup="placesTourniers()" value="3" class="form-control" name="">
					</div>
					<div class="col-lg-12">
						<div class="row" id="places_input_t">
							<div class="col-lg-3 mb-3">
								<label>Premio por el puesto 1</label>
								<input type="" id="place_1_t" value="500" class="form-control" name="">
							</div>
							<div class="col-lg-3 mb-3">
								<label>Premio por el puesto 2</label>
								<input type="" id="place_2_t" value="300" class="form-control" name="">
							</div>
							<div class="col-lg-3 mb-3">
								<label>Premio por el puesto 3</label>
								<input type="" id="place_3_t" value="200" class="form-control" name="">
							</div>
						</div>
					</div>				
					<div class="col-lg-9 mb-3">
						<label>Descripcion</label>
						<textarea type="" id="desc_t" class="form-control" name="">Torneo del modo Mines. Cuanto mayor sea la suma total de tus ganancias al final del torneo, mayor sera tu premio.</textarea>
					</div>
					<div class="col-lg-3 mb-3">
						<label>Accion</label>
						<button onclick="createTournier()" class="btn btn-info btn-block w-100">Crear torneo</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">

				<div class="table-responsive">
					<table class="table "  style="margin-bottom: 20px;"> 

						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Nombre</th>
								<th scope="col">Ganadores</th>
								<th scope="col">Premios</th>
								<th scope="col">Inicio</th>
								<th scope="col">Fin</th>
								<th scope="col">Juego</th>
								<th scope="col">Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data['tourniers'] as $t)
							
							<tr>
								<th scope="row">{{$t->id}}</th>
								<td>{{$t->name}}</td>
								<td>{{$t->places}}</td>
								<td>{{json_encode($t->prizes)}}</td>
								<td>{{date('d.m.Y h:i:s',$t->start)}}</td>
								<td>{{date('d.m.Y h:i:s',$t->end)}}</td>
								<td>{{$t->game}}</td>
								
								<th scope="col"><button onclick="deleteTournier({{$t->id}})" class="btn btn-danger btn-sm">Eliminar</button></th>
								
							</tr>
							@endforeach

						</tbody>
					</table>

					
				</div>


			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="/assets/js/pages/dashboard.init.js?v={{time()}}"></script>
@endsection
