@auth
@php
$userStatus = \Auth::user()->status;
$name_surname = explode(' ', \Auth::user()->name);
if($userStatus != 0){
	$status = \App\Status::where('id', $userStatus)->first();
	
}
$gamesAll = round(\Auth::user()->win_games + \Auth::user()->lose_games);

@endphp
<div class="wrapper">
	<div style="margin-top: 20px" class="profile d-flex align-start flex-wrap">
		<div class="profile__user d-flex flex-column align-center justify-center">
			<div class="profile__top d-flex align-center justify-space-between">
				<b>Perfil</b>
				<a href="https://vk.com/id{{\Auth::user()->vk_id}}" target="_blank" class="d-flex align-center">
					<svg class="icon small"><use xlink:href="images/symbols.svg#vk"></use></svg>
					<span>Perfil</span>
				</a>
			</div>
			<div class="profile__avatar d-flex justify-center align-center">
				<div class="profile__avatar-ellipse d-flex justify-center align-center">
					<div class="profile__avatar-img" style="    background: url({{\Auth::user()->avatar}}) no-repeat center center / cover;"></div>
				</div>
			</div>
			<div class="profile__username d-flex flex-column align-center justify-center">
				<b>{{\Auth::user()->name}}</b>
				<span>Id: {{\Auth::user()->id}}</span>
			</div>
			<div class="profile__balance  align-center justify-space-between">
				<div class="d-flex align-center justify-center" style="margin-bottom: 15px;">
					<svg class="icon money"><use xlink:href="images/symbols.svg#coins"></use></svg>
					<span>{{number_format(\Auth::user()->balance, 2, '.', ' ')}}</span>
				</div>
				<a href="#"  rel="popup" data-popup="popup--wallet" onclick="return false;" class="btn btn--blue d-flex align-center justify-center is-ripples flare"><span>Depositar</span></a>
			</div>
		</div>
		<div class="profile__stats">
			<div class="profile__stat-item d-flex flex-column">
				<b>Depósito</b>
				<span>{{number_format(\Auth::user()->deps, 2, '.', ' ')}}</span>
			</div>
			<div class="profile__stat-item d-flex flex-column">
				<b>Retiro</b>
				<span>{{number_format(\Auth::user()->withdraws, 2, '.', ' ')}}</span>
			</div>
			<div class="profile__stat-item d-flex flex-column">
				<b>Ganancia total</b>
				<span>{{number_format(\Auth::user()->sum_win, 2, '.', ' ')}}</span>
			</div>
			<div class="profile__stat-item d-flex flex-column">
				<b>Ganancia máxima</b>
				<span>{{number_format(\Auth::user()->max_win, 2, '.', ' ')}}</span>
			</div>
			<div class="profile__stat-item d-flex flex-column">
				<b>Ranking de victorias</b>
				<span>@if($gamesAll == 0) 0 @else{{(round(\Auth::user()->win_games / $gamesAll, 2) * 100)}}@endif %</span>
			</div>
			<div class="profile__stat-item d-flex flex-column">
				<b>Apuesta promedio</b>
				<span>@if($gamesAll == 0) 0 @else{{(round(\Auth::user()->sum_bet / $gamesAll, 2))}} @endif</span>
			</div>
			<div class="profile__stat-item d-flex flex-column">
				<b>Total de juegos</b>
				<span>{{(\Auth::user()->win_games + \Auth::user()->lose_games)}}</span>
			</div>
			<div class="profile__stat-item d-flex flex-column">
				<b>Total de victorias</b>
				<span>{{(\Auth::user()->win_games)}}</span>
			</div>
			<div class="profile__stat-item d-flex flex-column">
				<b>Total de derrotas</b>
				<span>{{(\Auth::user()->lose_games)}}</span>
			</div>
		</div>
	</div>
	<div class="profile__settings d-flex align-center justify-space-between">
		@if(\Auth::user()->admin == 3 or \Auth::user()->admin == 1)
		<div class="profile__settings-check  align-center" style="width:50%">
			<b style="font-weight: 600;">Configuración de saldo</b><br>
			<div class="dice__select-chance d-flex align-center justify-space-between" style="width:100%">

				<a  class="@if(\Auth::user()->type_balance == 0) active @endif" onclick="changeBalance('0', this)">Saldo real</a>
				<a class="@if(\Auth::user()->type_balance == 1) active @endif" onclick="changeBalance('1', this)">Saldo demo</a>
			</div> <br>
			@if(\Auth::user()->admin == 1)
			<div class="profile__stat-item d-flex flex-column" id="demoPanel"  style="@if(\Auth::user()->type_balance == 0) display: none; @endif">
				<b>Recargar saldo demo</b>
				<a rel="popup" data-popup="popup--demo-add" class="btn btn--blue d-flex align-center justify-center is-ripples flare"><span style="font-size: 14px;">Depositar</span></a>
			</div>
			@endif
		</div>


 

		@endif
		<!-- <div class="profile__settings-check d-flex align-center">
			<input type="checkbox" class="custom-checkbox" id="avatars" onchange="changeSetting(1)"  name="avatars">
			<label for="avatars">No mostrar avatares de otros</label>
		</div> -->
	</div>
</div>
<script type="text/javascript">
	function changeSetting(id) {
		if($('#avatars').is(":checked")){
			localStorage.setItem('avatars', 1);
			if(id == 1){
				$('body').addClass('blur_img')
			}
		}else{
			localStorage.setItem('avatars', 0);
			if(id == 1){
				$('body').removeClass('blur_img')
			}
		}
	}
	if(localStorage.getItem('avatars') == 1){
		$('#avatars').attr('checked', '')
	}





</script>
@else
<script type="text/javascript">location.href='/';</script>
@endauth
