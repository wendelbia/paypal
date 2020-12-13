@extends('site.layouts.app')

@section('title', 'Meu perfil')

@section('content')

<h1>Meu Perfil</h1>
<!--39 notificação de erro ou sucesso-->
<!--42 chamo o alert que está centralizado em admin/includes/alerts.blade
@if(session('success'))
		<div class="alert alert-success">
			{{ session('success') }}
		</div>
@endif

@if (session('error'))
		<div class="alert alert-danger">
			{{ session('error') }}
		</div>
@endif
-->
<!--42 incluo-->
@include('admin.includes.alerts')
<!--38 defino a rota e método  e em web crio essa rota-->
<form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data">
	{!! csrf_field() !!}
	<div class="form-group">
		<!--38 uso o value para imprimir os nome nos inputs-->
		<label for="name">Nome</label>
		<input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" placeholder="Nome">
	</div>

	<div class="form-group">
		<label for="email">Email</label>
		<input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control" placeholder="Email">
	</div>

	<div class="form-group">
		<label for="password">Senha</label>
		<input type="password" name="password" class="form-control" placeholder="Senha">
	</div>

	<div class="form-group">
		<!--41 exibir a imagem, se houver imagem, se ñ for nula então exibi-->
		<div class="form-group">
			@if (auth()->user()->image != null)
				<!--41 mostro o caminho onde será armazenado a imagem e pego a imagem do usu logado, agora vamos fazer a edição do perfil do usu, indo no cdm e fazendo o arquivo de validação: php artisan make:request UpdateFrofileFormRequest e config o arquivo-->
				<img src="{{ url('storage/users/'.auth()->user()->image)}}" alt="{{ auth()->user()->name }}" style="max-width: 50px;">
			@endif
		</div>

		<label for="image">Imagem:</label>
        <input type="file" name="image" class="form-control">
	</div>

	<div class="form-group">
		<button type="submit" class="btn btn-info">Atualizar Perfil</button>
	</div>
</form>
@endsection