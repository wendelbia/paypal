@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Depósito')

@section('content_header')
    <h1>Depósito</h1>

    <ol class="breadcrumb">
    	<li><a href="">Dashboard</a></li>
    	<li><a href="">Saldo</a></li>
    	<li><a href="">Depósito</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
    	<div class="box-header">
    		<h3>Fazer Recarga</h3>
    	</div>
    	<div class="box-body">
    		<!--23 mensagens de erro, erro caso tenha (any) e agora vou no balanceController para dev a view de exibição de mensagem#
    		@if ($errors->any())
    			<div class="alert alert-warning">
    				@foreach ($errors->all() as $error)
    					<p>{{ $error }}</p>
    				@endforeach
    			</div>
    		@endif-->

    		<!--#24 incluo o alert.blade.php e depois em balande/index.blade.php-->
    		@include('admin.includes.alerts')
    		<!--#18 faço o formulário uso o post-->
    		<form method="POST" action="{{ route('deposit.store')}}">
    			<!--#18 crio um helper q é usado para proteção e temos o csrf_token() q é o própio token-->
    			{!! csrf_field() !!}
    			<div class="form-group">
    			<!--#19 acrescentamos o name="" e vou o no controller-->
    				<input name="value" type="text" placeholder="Valor Recarga" class="form-control">
    			</div>
    			<div class="form-group">
    				<button type="submit" class="btn btn-success">Depositar</button>
    			</div>
    		</form>
    	</div>
    </div>
@stop