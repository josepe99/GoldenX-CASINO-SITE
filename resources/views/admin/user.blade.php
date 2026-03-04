@extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

@component('admin.components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Dashboard @endslot
@endcomponent

@php
$user = $data['user'];
@endphp
<div class="row">
    <div class="col-xl-4">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">Profile №{{$user->id}}</h4>
          <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
      </div>
      <div class="card-body">
          <form>
            <div class="row mb-2">
              <div class="profile-title">
                <div class="media" style="align-items: center;">      
                    <div><img class="img-70 rounded-circle" style="width: 100px;height: 100px;" alt="" src="{{$user->avatar}}"></div>                  

                    <div class="media-body ms-3">
                        <h5 class="mb-1">{{$user->name ?? $user->email}}</h5>
                        <p>@if($user->admin == 1) Administrador @else Usuario @endif</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Saldo</label>
            <input class="form-control" disabled id="balance_2" value="{{number_format($user->balance, 2, ',', ' ')}}">
        </div>
      <div class="mb-3">
          <label class="form-label">IP</label>
          <input class="form-control" disabled value="{{$user->ip}}">
      </div>

      <div class="mb-3">
          <label class="form-label">VK</label>
          <input class="form-control" disabled value="{{$user->social}}">
      </div>
      <div class="mb-3">
          <label class="form-label">Estado</label>
          <input class="form-control" disabled value="{{$user->status == 0 ? 'Novato' : ($user->status == 1 ? 'Lobo' : ($user->status == 2 ? 'Depredador' : ($user->status == 3 ? 'Premium' : ($user->status == 4 ? 'Alfa' : ($user->status == 5 ? 'VIP' : ($user->status == 6 ? 'Pro' : 'Leyenda'))))))}}">
      </div>
      <div class="mb-3">
          <label class="form-label">Tarjeta grafica</label>
          <input class="form-control" disabled value="{{$user->videocard}}">
      </div>

      <div class="mb-3">
          <label class="form-label">Referidos</label>
          <input class="form-control" disabled value="{{$user->refs}}">
      </div>

      <div class="mb-3">
          <label class="form-label">Depositado</label>
          <input class="form-control" disabled value="{{$user->deps}}">
      </div>

      <div class="mb-3">
          <label class="form-label">Retirado</label>
          <input class="form-control" disabled value="{{$user->withdraws}}">
      </div>

      <div class="mb-3">
          <label class="form-label">Fecha de registro</label>
          <input class="form-control" disabled value="{{date('d.m.y H:i:s', strtotime($user->created_at))}}">
      </div>


      <div class="row">
        <div class="col-6">
            @if($user->ban == 1)<button type="button" onclick="changeBan({{$user->id}}, 0)" class="btn btn-success w-100">Desbloquear</button>@else<button type="button" onclick="changeBan({{$user->id}}, 1)" class="btn btn-danger w-100">Bloquear</button>@endif
        </div>
        <div class="col-6"><button class="btn btn-danger w-100" type="button" onclick="deleteUser({{$user->id}})">Eliminar cuenta</button></div>
    </div>

</form>
</div>
</div>
</div>
<div class="col-xl-8">
  <form class="card">
    <div class="card-header">
      <h4 class="card-title mb-0">Edit Profile</h4>
      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Saldo</label>
                <input class="form-control" type="text" value="{{$user->balance}}" id="balance" placeholder="Saldo">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Saldo demo</label>
                <input class="form-control" type="text" value="{{$user->demo_balance}}" id="demo_balance" placeholder="Saldo">
            </div>
        </div>

    <div class="col-md-6">
      <div class="mb-3">
        <label class="form-label">Rol</label>
        <select class="form-control" id="admin" value="{{$user->admin}}"> 
            <option value="0" {{ $user->admin == 0 ? 'selected' : ''}}>Usuario</option>     
            <option value="1" {{ $user->admin == 1 ? 'selected' : ''}}>Administrador</option>                               
            <option value="2" {{ $user->admin == 2 ? 'selected' : ''}}>Moderador</option> 
            <option value="3" {{ $user->admin == 3 ? 'selected' : ''}}>Youtuber</option> 
        </select>
    </div>
</div>
</div>
</div>
<div class="card-footer text-end">
  <button class="btn btn-primary" onclick="saveUser({{$user->id}})" type="button">Guardar</button>
</div>
</form>
<div class="card">
    <div class="card-header">
      <h4 class="card-title mb-0">Cuentas</h4>
      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
  </div>
  <div class="card-body">
  <div class="table-responsive add-project">

<table class="table "  style="margin-bottom: 20px;"> 

  <thead>
      <tr>
          <th scope="col">#</th>
          <th scope="col">Usuario</th>
          <th scope="col">Fecha de registro</th>
          <th scope="col">Acciones</th>

      </tr>
  </thead>
  <tbody>
      @foreach($data['accounts'] as $acc)
      <tr>
          <th scope="row">{{$acc->id}}</th>
          <td><img src="{{$acc->avatar}}" style="width:30px;height:30px;border-radius: 100%" class="me-3"><a href="/admin/user/{{$acc->id}}" target="_blank" @if($acc->admin == 1) class="text-danger" @endif>{{$acc->name}}</a></td>         
          <td>{{date('d.m.y H:i:s', strtotime($acc->created_at))}}</td>
          <th scope="col">@if($acc->ban == 0)<button onclick="changeBan({{$acc->id}}, 1)" class="btn btn-info btn-sm">Bloquear</button> @else<button onclick="changeBan({{$acc->id}}, 0)" class="btn btn-info btn-sm">Desbloquear</button> @endif</th>

      </tr>
      @endforeach

  </tbody>
</table>

<div style="margin-bottom: 5px;">
  {{ $data['accounts']->links() }}
</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-md-6">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title mb-0">Depositos</h4>
      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
  </div>
  <div class="table-responsive add-project">

      <table class="table "  style="margin-bottom: 20px;"> 

        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Usuario</th>
                <th scope="col">Sistema</th>
                <th scope="col">Monto</th>
                
                <th scope="col">Fecha</th>

                <th scope="col">Acciones</th>

            </tr>
        </thead>
        <tbody>
            @foreach($data['deps'] as $d)
            @php
            $u = \App\User::where('id', $d->user_id)->first();
            @endphp
            <tr>
                <th scope="row">{{$d->id}}</th>
                <td><img src="{{$u->avatar}}" style="width:30px;height:30px;border-radius: 100%" class="me-3"><a href="{{$u->social}}" target="_blank" @if($u->admin == 1) class="text-danger" @endif>{{$u->name}}</a></td>
                <td><img src="../{{$d->img_system}}" style="width: 30px;"></td>
                <td>{{number_format($d->sum, 2, ',', ' ')}}</td>
                
                <td>{{date('d.m.y H:i:s', strtotime($d->created_at))}}</td>

                <th scope="col">@if($d['status'] == 0)<button onclick="changePay({{$d->id}})" class="btn btn-info btn-sm">Acreditar deposito</button>@endif</th>

            </tr>
            @endforeach

        </tbody>
    </table>

    <div style="margin-bottom: 5px;">
        {{ $data['deps']->links() }}
    </div>
</div>
</div>
</div>
<div class="col-md-6">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title mb-0">Retiros</h4>
      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
  </div>
  <div class="table-responsive add-project">

      <table class="table "  style="margin-bottom: 20px;"> 

        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Usuario</th>
                <th scope="col">Sistema</th>
                <th scope="col">Monto</th>
                <th scope="col">Billetera</th>
                <th scope="col">Fecha</th>
                <th scope="col">Acciones</th>

            </tr>
        </thead>
        <tbody>
            @foreach($data['withdraws'] as $w)
            @php
            $u = \App\User::where('id', $w->user_id)->first();
            @endphp
            <tr>
                <th scope="row">{{$w->id}}</th>
                <td><img src="{{$u->avatar}}" style="width:30px;height:30px;border-radius: 100%" class="me-3"><a href="{{$u->social}}" target="_blank" @if($u->admin == 1) class="text-danger" @endif>{{$u->name}}</a></td>
                <th scope="row">{{$w->ps}}</th>
                <td>{{number_format($w->sum, 2, ',', ' ')}}</td>
                <th scope="row">{{$w->wallet}}</th>
                <td>{{date('d.m.y H:i:s', strtotime($w->created_at))}}</td>

                <th scope="col">@if($w['status'] == 0)<button onclick="changeWithdraw({{$w->id}}, 1)" class="btn btn-info btn-sm">Retirar</button>@endif</th>

            </tr>
            @endforeach

        </tbody>
    </table>

    <div style="margin-bottom: 5px;">
        {{ $data['withdraws']->links() }}
    </div>
</div>
</div>
</div>

<div class="col-md-12">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title mb-0">Historial de saldo</h4>
      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
  </div>
  <div class="table-responsive add-project">

      <table class="table "  style="margin-bottom: 20px;"> 

        <thead>
            <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Accion</th>
                <th scope="col">Saldo anterior</th>
                <th scope="col">Saldo posterior</th>
                <th scope="col">Cambio de saldo</th>
                <th scope="col">Fecha</th>

            </tr>
        </thead>
        <tbody>
            @foreach($data['history'] as $h)
            <tr>
                <td>{{$h->type}}</td>
                <td></td>
                <td>{{number_format($h->balance_before, 2, ',', ' ')}}</td>
                <td>{{number_format($h->balance_after, 2, ',', ' ')}}</td>
                <td>{{number_format(($h->balance_before - $h->balance_after), 2, ',', ' ')}}</td>
                <td>{{date('d.m.y H:i:s', strtotime($h->date))}}</td>

             
            </tr>
            @endforeach

        </tbody>
    </table>

    <div style="margin-bottom: 5px;">
        {{ $data['history']->links() }}
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
