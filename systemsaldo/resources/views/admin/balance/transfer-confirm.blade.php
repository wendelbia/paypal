@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Confiramação de Transferência')

@section('content_header')
    <h1>Transferência</h1>

    <ol class="breadcrumb">
    	<li><a href="">Dashboard</a></li>
    	<li><a href="">Saldo</a></li>
        <li><a href="">Transferir</a></li>
        <li><a href="">Confirmação</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
    	<div class="box-header">
    		<h3>Confirmação de Transferir)</h3>
    	</div>
    	<div class="box-body">
    		<!--#27 incluo o alert.blade.php e depois em balande/index.blade.php-->
    		@include('admin.includes.alerts')
            <!--28 nome do recebedor-->
            <p><strong>Recebedor: </strong>{{ $sender->name }}</p>
            <!--28 nome do recebedor-->
            <p><strong>Seu Saldo Atual: </strong>{{ number_format($balance->amount, 2, ',', '.') }}</p>
    		<!--#28 faço o formulário uso o post e faço a rota-->
    		<form method="POST" action="{{ route('transfer.store')}}">
    			{!! csrf_field() !!}
                <!--29 pasando o id de forma oculta q é importante para saber quem vai receber esse vl-->
                <input type="hidden" name="sender_id" value="{{ $sender->id }}">
    			<div class="form-group">
    				<input name="value" type="text" placeholder="Valor" class="form-control">
    			</div>
    			<div class="form-group">
    				<button type="submit" class="btn btn-success">Transferir</button>
    			</div>
    		</form>
    	</div>
    </div>
@stop