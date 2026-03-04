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
					<div class="col-lg-3">
						<label>Nombre del codigo promo</label>
						<input type="" id="name_promo" class="form-control" name="">
					</div>
					<div class="col-lg-3">
						<label>Monto</label>
						<input type="" id="sum_promo" class="form-control" name="">
					</div>
					<div class="col-lg-3">
						<label>Activaciones</label>
						<input type="" id="active_promo" class="form-control" name="">
					</div>
					<div class="col-lg-3">
						<label>Accion</label>
						<button onclick="createPromo()" class="btn btn-info btn-block w-100">Crear codigo promo</button>
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
								<th scope="col">Creador</th>
								<th scope="col">Nombre</th>
								<th scope="col">Monto</th>
								<th scope="col">Activaciones</th>
								<th scope="col">Fecha</th>
								<th scope="col">Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data['promo'] as $p)
							@php
								$active = \Cache::get('promo.name.'.$p->name.'.active');
						        $actived = \Cache::get('promo.name.'.$p->name.'.active.count');
						        $sum = \Cache::get('promo.name.'.$p->name.'.sum');
							@endphp
							<tr>
								<th scope="row">{{$p->id}}</th>
								<td>{{$p->user_name}}</td>
								<th scope="row">{{$p->name}}</th>
								<td>{{number_format($sum, 2, ',', ' ')}}</td>
								<th scope="row">{{$actived}} / {{$active}}</th>
								<td>{{date('d.m.y H:i:s', strtotime($p->created_at))}}</td>
								<th scope="col"><button onclick="deletePromo({{$p->id}})" class="btn btn-danger btn-sm">Eliminar</button></th>
								
							</tr>
							@endforeach

						</tbody>
					</table>

					<div style="margin-bottom: 5px;">
						{{ $data['promo']->links() }}
					</div>

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
